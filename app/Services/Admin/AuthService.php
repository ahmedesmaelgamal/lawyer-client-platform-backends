<?php

namespace App\Services\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtp;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FA\Google2FA;


class AuthService
{

    public function __construct(protected Google2FA $google2fa)
    {
        // Constructor code if needed
    }

    public function index()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('adminHome');
        }
        return view('admin.auth.login');
    }

   public function login( $request)
{
    try {
        $data = $request->validate([
            'input' => 'required',
            'password' => 'required',
        ], [
            'password.required' => trns('password_required'),
        ]);

        $admin = Admin::where('user_name', $data['input'])
            ->orWhere('email', $data['input'])
            ->first();

        if (!$admin || !Hash::check($data['password'], $admin->password)) {
            return response()->json([
                'status' => 401,
                'message' => trns('invalid_credentials')
            ]);
        }

        // Check if 2FA enabled
        if ($admin->twofa_verify == 1) {
            return response()->json([
                'status' => 202,
                'message' => trns('two_fa_required'),
                'user_id' => $admin->id
            ]);
        }

        Auth::guard('admin')->login($admin);

        return response()->json([
            'status' => 200,
            'message' => trns('login_success'),
            'redirect' => route('adminHome')
        ]);

    } catch (Exception $e) {
        Log::error('Login error: ' . $e->getMessage());
        return response()->json([
            'status' => 500,
            'message' => trns('something_went_wrong')
        ]);
    }
}

public function verify2fa( $request)
{
    try {
        $request->validate([
            'twofa_code' => 'required|digits:6',
            'user_id' => 'required|exists:admins,id'
        ]);

        $admin = Admin::find($request->user_id);
        $secret = decrypt($admin->twofa_secret);

        $valid = $this->google2fa->verifyKey($secret, $request->twofa_code);

        if ($valid) {
            Auth::guard('admin')->login($admin);

            return response()->json([
                'status' => 200,
                'message' => trns('two_fa_success'),
                'redirect' => route('adminHome')
            ]);
        }

        return response()->json([
            'status' => 401,
            'message' => trns('invalid_code')
        ]);

    } catch (Exception $e) {
        Log::error('2FA error: ' . $e->getMessage());
        return response()->json([
            'status' => 500,
            'message' => trns('something_went_wrong')
        ]);
    }
}


    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function showForgetPassword()
    {
        return view('admin.auth.resetPassword');
    }

    public function ForgetPassword($request)
    {

        $admin = Admin::where('email', $request['email'])->first();

        if ($admin) {
            $otp = rand(1000, 9999);
            $otp_expire_at = now()->addMinutes(3);
            $admin->otp = $otp;
            $admin->otp_expire_at = $otp_expire_at;
            $admin->save();

            Mail::to($admin->email)->send(new SendOtp($otp, $otp_expire_at));
            return view('admin.auth.enterOtp', ['admin' => $admin, 'email' => $request['email']]);
        } else {
            return redirect()->route('admin.showForgetPassword')->with('error', trns('email_not_found'));
        }
    }

    public function verifyOtp($request)
    {
        $email = $request['email'];
        $otp = $request['otp'];

        $admin = Admin::where('email', $email)->where('otp', $otp)->first();
        if ($admin) {
            return view('admin.auth.updatePassword', ['admin' => $admin, 'email' => $email]);
        } else {
            // return Route::has('admin.showForgetPassword') ? redirect()->route('admin.showForgetPassword') : redirect()->route('admin.showForgetPassword')->with('error', trns('invalid_otp'));
            return view('admin.auth.enterOtp', ['admin' => Admin::where('email', $email)->first(), 'email' => $request['email'], 'error' => trns('invalid_otp')]);
        }
    }

    public function UpdatePassword($request)
    {
        $password = $request['New_Password'];
        $admin = Admin::where('email', $request['email'])->first();
        $admin->password = Hash::make($password);
        $admin->save();
        Auth::guard('admin')->login($admin);
        return redirect()->route('adminHome');
    }
}
