<?php

namespace App\Http\Controllers\Api\Lawyer\Auth;

use App\Http\Controllers\Controller;
use App\Services\Api\Lawyer\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService){}
    public function login(Request $request)
    {
        return $this->authService->login($request);
    }
    public function register(Request $request)
    {
        return $this->authService->register($request);
    }
    public function sendOtp(Request $request)
    {
        return $this->authService->sendOtp($request);
    }
    public function checkOtp(Request $request)
    {
        return $this->authService->checkOtp($request);
    }
    public function logout(Request $request)
    {
        return $this->authService->logout($request);
    }
    public function profile()
    {
        return $this->authService->profile();

    }
    public function updateProfile(Request $request)
    {
        return $this->authService->updateProfile($request);
    }
    public function  lawyerWorkTimes(Request $request)
    {
        return $this->authService->lawyerWorkTimes($request);
    }
    public function  getLawyerWorkTimes()
    {
        return $this->authService->getLawyerWorkTimes();
    }

    public function  updateLawyerWorkTimesStatus(Request $request)
    {
        return $this->authService->updateLawyerWorkTimesStatus($request);
    }
    public function updateEmail(Request $request)
    {
        return $this->authService->updateEmail($request);
    }

    public function resetPassword(Request $request)
    {
        return $this->authService->resetPassword($request);
    }
    public function changePassword(Request $request)
    {
        return $this->authService->changePassword($request);

    }
}
