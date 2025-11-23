<?php

namespace App\Services\Api\Client;

use App\Enums\StatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Resources\ClientResource;
use App\Mail\SendOtpMail;
use App\Models\ClientPoint;
use App\Models\DeviceToken;
use App\Models\Client as ObjModel;
use App\Models\Setting;
use App\Services\Admin\EmailOtpService;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Summary of AuthService
 */
class AuthService extends BaseService
{
    /**
     * Constructor method
     *
     * @param ObjModel $model
     * @param EmailOtpService $emailOtpService
     *
     * @return void
     */
    public function __construct(ObjModel $model, protected EmailOtpService $emailOtpService, protected ClientPoint $clientPoint, protected Setting $settings)
    {
        parent::__construct($model);
    }


    /**
     * Register a new user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:clients,email',
                'password' => 'required|string|min:6',
                'national_id' => 'nullable|string|max:20|unique:clients,national_id',
                'city_id' => 'nullable|exists:cities,id',
                'country_id' => 'nullable|exists:countries,id',
                'phone' => 'nullable|string|max:15|unique:clients,phone',
                'country_code' => 'required|string|max:5',
                'commercial_code' => 'nullable|string|max:20|exists:client_points,commercial_code',
            ]);

            if ($validator) {
                return $validator;
            }

            \DB::beginTransaction();

            $data = $request->all();
            $data['password'] = Hash::make($request->password);
            $data['status'] = StatusEnum::ACTIVE->value;

            $user = $this->model->create($data);

            if (isset($request->commercial_code)) {
                $clientPoint = $this->clientPoint->where('commercial_code', $request->commercial_code)->first();
                if (!$clientPoint) {
                    \DB::rollBack();
                    return $this->responseMsg('Commercial code not found', null, 404);
                }
                //                dd($this->settings->where('key', 'referral_receiver_points')->first());
                $this->clientPoint->create([
                    'client_id' => $user->id,
                    'commercial_code' => fake()->lexify('??????????'),
                    'points' => $this->settings->where('key', 'referral_receiver_points')->first()->value,
                    'entered_with_code' => $request->commercial_code,
                ]);
                $senderClient = $this->clientPoint->where('commercial_code', $request->commercial_code)->first();
                $senderClient->update([
                    'points' => $senderClient->points + $this->settings->where('key', 'referral_sender_points')->first()->value,
                ]);
            } else {
                $this->clientPoint->create([
                    'client_id' => $user->id,
                    'commercial_code' => fake()->lexify('??????????'),
                    'entered_with_code' => null,
                ]);
            }

            if ($user) {
                $token = Auth::guard('client_api')->attempt(['email' => $request->email, 'password' => $request->password]);

                if ($token) {
                    DeviceToken::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'mac_address' => $request->mac_address,
                            'device_type' => $request->device_type,
                            'user_type' => UserTypeEnum::CLIENT->value,
                        ],
                        [
                            'token' => $request->device_token,
                            'user_id' => $user->id,
                            'device_type' => $request->device_type,
                            'mac_address' => $request->mac_address,
                            'user_type' => UserTypeEnum::CLIENT->value,
                        ]
                    );
                    $user['token'] = 'Bearer ' . $token;
                    \DB::commit();
                    return $this->responseMsg('User registered successfully', ClientResource::make($user));
                } else {
                    \DB::rollBack();
                    return $this->responseMsg('User registered successfully but login failed', [
                        'user' => $user
                    ], 401);
                }
            } else {
                \DB::rollBack();
                return $this->responseMsg('User registration failed', null, 500);
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->responseMsg('User registration failed', null, 500);
        }
    }

    /**
     * Login a user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator) {
                return $validator;
            }

            $token = Auth::guard('client_api')->attempt([
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($token) {
                $user = Auth::guard('client_api')->user();

                DeviceToken::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'mac_address' => $request->mac_address,
                        'device_type' => $request->device_type,
                        'user_type' => UserTypeEnum::CLIENT->value
                    ],
                    [
                        'token' => $request->device_token,
                        'user_id' => $user->id,
                        'device_type' => $request->device_type,
                        'mac_address' => $request->mac_address,
                        'user_type' => UserTypeEnum::CLIENT->value,
                    ]
                );
                $user['token'] = 'Bearer ' . $token;
                return $this->responseMsg('Login successful', ClientResource::make($user));
            } else {
                return $this->responseMsg('Login failed, check your credentials', null, 422);
            }
        } catch (\Exception $e) {
            return $this->responseMsg('Login failed', null, 500);
        }
    }

    /**
     * Send OTP to user email.
     * @param mixed $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function sendOtp($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'email' => 'required|email',
            ]);

            if ($validator) {
                return $validator;
            }

            $otp = rand(100000, 999999);

            $checkEmail = $this->emailOtpService->model->where('email', $request->email)
                ->where('is_verified', '!=', 1)
                ->whereDate('otp_expire', '>=', \Carbon\Carbon::now())
                ->first();

            if ($checkEmail) {
                return $this->responseMsg('OTP already sent', ['otp' => (int)$checkEmail['otp']], 201);
            }
            $data = $this->emailOtpService->model->create([
                'email' => $request->email,
                'otp' => $otp,
                'otp_expire' => \Carbon\Carbon::now()->addMinutes(5),
            ]);

            $sendEmail = Mail::to($request->email)->send(new SendOtpMail($data));

            if ($sendEmail) {
                return $this->responseMsg('OTP sent successfully', [
                    'otp' => (int)$otp
                ]);
            } else {
                return $this->responseMsg('OTP sending failed', null, 500);
            }
        } catch (\Exception $e) {
            return $this->responseMsg('OTP sending failed', null, 500);
        }
    }

    /**
     * Check OTP.
     * @param mixed $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function checkOtp($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'otp' => 'required|max:6',
                'email' => 'required|email',
            ]);

            if ($validator) {
                return $validator;
            }

            $otp = $request->otp;
            $email = $request->email;

            $emailOtp = $this->emailOtpService->model->where('email', $email)
                ->where('otp', $otp)
                ->where('is_verified', '!=', 1)
                ->whereDate('otp_expire', '>=', \Carbon\Carbon::now())
                ->first();

            if ($emailOtp) {
                $emailOtp->is_verified = 1;
                $emailOtp->save();
                return $this->responseMsg('OTP is valid', true);
            } else {
                return $this->responseMsg('OTP is invalid', false, 422);
            }
        } catch (\Exception $e) {
            return $this->responseMsg('OTP is invalid', false, 422);
        }
    }

    /**
     * Get user profile.
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        try {
            $user = Auth::guard('client_api')->user();
            return $this->responseMsg('User profile', ClientResource::make($user));
        } catch (\Exception $e) {
            return $this->responseMsg('User profile not found', null, 404);
        }
    }

    /**
     * Summary of updateProfile
     * @param mixed $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function updateProfile($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'name' => 'required|string',
                'country_id' => 'nullable|exists:countries,id',
                'city_id' => 'nullable|exists:cities,id',
                'image' => 'nullable|image',
                'national_id' => 'nullable|integer|unique:clients,national_id,' . Auth::guard('client_api')->id(),
                'phone' => 'nullable|string|unique:clients,phone,' . Auth::guard('client_api')->id(),
            ]);

            if ($validator) {
                return $validator;
            }

            $image = null;
            if ($request->hasFile('image')) {
                $image = $this->handleFile($request->file('image'), 'clients');
            }

            $user = $this->model->find(Auth::guard('client_api')->id());
            $user->name = $request->name;
            $user->country_id = $request->country_id;
            $user->city_id = $request->city_id;
            $user->image = $image;
            $user->national_id = $request->national_id;
            $user->phone = $request->phone;
            if ($user->save()) {
                return $this->responseMsg('User profile updated', ClientResource::make($user));
            } else {
                return $this->responseMsg('User profile update failed', null, 500);
            }
        } catch (\Exception $e) {
            return $this->responseMsg('User profile update failed', null, 500);
        }
    }

    /**
     * Update user email.
     * @param mixed $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function updateEmail($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'email' => 'required|email|unique:clients,email,' . Auth::guard('client_api')->id(),
            ]);

            if ($validator) {
                return $validator;
            }

            $user = $this->model->find(Auth::guard('client_api')->id());
            $user->email = $request->email;

            if ($user->save()) {
                return $this->responseMsg('User email updated', ClientResource::make($user));
            } else {
                return $this->responseMsg('User email update failed', null, 500);
            }
        } catch (\Exception $e) {
            return $this->responseMsg('User email update failed', null, 500);
        }
    }


    /**
     * Reset user password.
     * @param mixed $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function resetPassword($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'email' => 'required|email|exists:clients,email',
                'password' => 'required|string|min:8',
            ]);

            if ($validator) {
                return $validator;
            }

            $emailOtp = $this->emailOtpService->model->where('email', $request->email)
                ->whereDate('otp_expire', '>=', \Carbon\Carbon::now())
                ->latest()->first();

            if ($emailOtp) {
                $user = $this->model->where('email', $request->email)->first();

                if ($user) {
                    $user->password = Hash::make($request->password);
                    if ($user->save()) {
                        return $this->responseMsg('Password reset successful', null);
                    } else {
                        return $this->responseMsg('Password reset failed', null, 500);
                    }
                } else {
                    return $this->responseMsg('User not found', null, 404);
                }
            } else {
                return $this->responseMsg('OTP is Expired', false, 422);
            }
        } catch (\Exception $e) {
            return $this->responseMsg('Password reset failed', null, 500);
        }
    }

    public function changePassword($request)
    {
        $user = $this->model->where('id', auth()->guard('lawyer_api')->user()->id)->first();
        try {
            $validator = $this->apiValidator($request->all(), [
                'old_password' => 'required|string',
                'new_password' => 'required|string|min:8',
                'new_password_confirmation' => 'required|string|min:8|same:new_password'
            ]);

            if ($validator) {
                return $validator;
            }
            if (!Hash::check($request->old_password, $user->password)) {
                return $this->responseMsg('Old password is incorrect', null, 422);
            }
            if ($user) {
                $user->password = Hash::make($request->new_password);
                if ($user->save()) {
                    return $this->responseMsg('Password changed successfully', null);
                } else {
                    return $this->responseMsg('error changing the password', null, 500);
                }
            } else {
                return $this->responseMsg('error changing password for this user', null, 404);
            }
        } catch (Exception $e) {
            return $this->responseMsg('Password changed successfully', null, 500);
        }
    }

    /**
     * Change user password.
     * @param mixed $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function logout($requset)
    {
        try {
            $user = Auth::guard('client_api')->user();
            $device = DeviceToken::where('user_id', $user->id)
                ->where('user_type', 'client')
                ->where('mac_address', $requset->mac_address)
                ->first();

            if ($device) {
                $device->delete();
            }

            Auth::guard('client_api')->logout();
            return $this->responseMsg('Logout successful', null);
        } catch (\Exception $e) {
            return $this->responseMsg('Logout failed', null, 500);
        }
    }
}
