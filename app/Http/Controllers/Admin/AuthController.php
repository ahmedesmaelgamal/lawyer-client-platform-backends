<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Interfaces\AuthInterface;
use App\Services\Admin\AuthService as ObjService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index()
    {
        return $this->objService->index();
    }

    public function login(Request $request)
    {
        return $this->objService->login($request);
    }

    public function verify2fa(Request $request)
    {
        return $this->objService->verify2fa($request);
    }

    public function logout()
    {
        return $this->objService->logout();
    }

    public function showForgetPassword()
    {
        return $this->objService->showForgetPassword();
    }

    public function ForgetPassword(ForgetPasswordRequest $request)
    {
        $data = $request->validated();
        return $this->objService->ForgetPassword($data);
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $data = $request->validated();
        return $this->objService->verifyOtp($data);
    }

    public function UpdatePassword(UpdatePasswordRequest $request)
    {
        $data = $request->validated();
        return $this->objService->UpdatePassword($data);
    }
}
