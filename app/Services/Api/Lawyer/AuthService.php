<?php

namespace App\Services\Api\Lawyer;

use App\Enums\LawyerStatusEnum;
use App\Enums\StatusEnum;
use App\Enums\UserTypeEnum;
use App\Enums\WeekDaysEnum;
use App\Http\Resources\LawyerResource;
use App\Http\Resources\LawyerTimeResource;
use App\Mail\SendOtpMail;
use App\Models\DeviceToken;
use App\Models\Lawyer as ObjModel;
use App\Models\LawyerAbout;
use App\Models\LawyerTime;
use App\Services\Admin\EmailOtpService;
use App\Services\Admin\LawyerAboutService;
use App\Services\Admin\LawyerSpecialityService;
use App\Services\Admin\LawyerTimeService;
use App\Services\BaseService;
use Exception;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

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
    public function __construct(
        ObjModel                          $model,
        protected EmailOtpService         $emailOtpService,
        protected LawyerAboutService      $lawyerAboutService,
        protected LawyerSpecialityService $lawyerSpecialityService,
        protected LawyerTimeService       $lawyerTimeService
    )
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
//        try {e
        $validator = $this->apiValidator($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:lawyers,email',
            'password' => 'required|string|min:6',
            'national_id' => 'nullable|string|max:20|unique:lawyers,national_id',
            'city_id' => 'required|exists:cities,id',
            'country_id' => 'required|exists:countries,id',
            'phone' => 'required|string|max:15|unique:lawyers,phone',
            'country_code' => 'required|string|max:5',
            'lawyer_id' => 'required',
            'type' => 'required|in:individual,office',
            'level_id' => 'required|exists:levels,id',
            'speciality_ids' => 'required|array|min:1',
        ]);

        if ($validator) {
            return $validator;
        }

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['status'] = StatusEnum::ACTIVE->value;

        $user = $this->model->create($data);
        foreach (WeekDaysEnum::values() as $weekDay) {
            $user->lawyerTimes()->create([
                'day' => $weekDay,
                'from' => '08:00',
                'to' => '14:00',
                'status' => StatusEnum::INACTIVE->value
            ]);
        }

        if ($user) {
            foreach ($request->speciality_ids as $speciality) {
                $this->lawyerSpecialityService->model->create([
                    'lawyer_id' => $user->id,
                    'speciality_id' => $speciality
                ]);
            }
        }

        if ($user) {
            $token = Auth::guard('lawyer_api')->attempt(['email' => $request->email, 'password' => $request->password]);

            if ($token) {
                DeviceToken::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'mac_address' => $request->mac_address,
                        'device_type' => $request->device_type,
                        'user_type' => UserTypeEnum::LAWYER->value
                    ],
                    [
                        'token' => $request->device_token,
                        'user_id' => $user->id,
                        'device_type' => $request->device_type,
                        'mac_address' => $request->mac_address,
                        'user_type' => UserTypeEnum::LAWYER->value,
                    ]
                );
                $user['token'] = 'Bearer ' . $token;
                LawyerAbout::create([
                    'lawyer_id' => $user->id
                ]);
                return $this->responseMsg('User registered successfully', LawyerResource::make($user));
            } else {
                return $this->responseMsg('User registered successfully but login failed', [
                    'user' => $user
                ], 401);
            }
        } else {
            return $this->responseMsg('User registration failed', null, 500);
        }
//        } catch (Exception $e) {
//            return $this->responseMsg('User registration failed', null, 500);
//        }
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

            $token = Auth::guard('lawyer_api')->attempt([
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($token) {
                $user = Auth::guard('lawyer_api')->user();

                DeviceToken::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'mac_address' => $request->mac_address,
                        'device_type' => $request->device_type,
                        'user_type' => UserTypeEnum::LAWYER->value
                    ],
                    [
                        'token' => $request->device_token,
                        'user_id' => $user->id,
                        'device_type' => $request->device_type,
                        'mac_address' => $request->mac_address,
                        'user_type' => UserTypeEnum::LAWYER->value,
                    ]
                );
                $user['token'] = 'Bearer ' . $token;
                return $this->responseMsg('Login successful', LawyerResource::make($user));
            } else {
                return $this->responseMsg('Login failed, check your credentials', null, 422);
            }
        } catch (Exception $e) {
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
        } catch (Exception $e) {
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
        } catch (Exception $e) {
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
            $user = Auth::guard('lawyer_api')->user();
            return $this->responseMsg('User profile', LawyerResource::make($user));
        } catch (Exception $e) {
            return $this->responseMsg('User profile not found', null, 404);
        }
    }

    /**
     * Update user profile.
     * @param mixed $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function updateProfile($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'name' => 'required|string',
                'country_id' => 'required|exists:countries,id',
                'city_id' => 'required|exists:cities,id',
                'image' => 'nullable|image',
                'type' => 'required|in:individual,office',
                'about' => 'nullable|string',
                'consultation_fee' => 'nullable|numeric',
                'attorney_fee' => 'nullable|numeric',

                'office_address' => 'nullable|string',
                'lat' => 'nullable',
                'lng' => 'nullable',

                'success_case' => 'nullable|integer',
                'failed_case' => 'nullable|integer',
                'public_work' => 'nullable|in:' . StatusEnum::ACTIVE->value . ',' . StatusEnum::INACTIVE->value,
            ]);

            if ($validator) {
                return $validator;
            }

            $image = null;
            if ($request->hasFile('image')) {
                $image = $this->handleFile($request->file('image'), 'lawyers');
            }

            $user = $this->model->find(Auth::guard('lawyer_api')->id());
            $user->name = $request->name;
            $user->country_id = $request->country_id;
            $user->city_id = $request->city_id;
            $user->image = $image;
            $user->type = $request->type;

            LawyerAbout::where('lawyer_id', $user->id)->update([
                'lawyer_id' => $user->id,
                'about' => $request->about,
                'consultation_fee' => $request->consultation_fee,
                'attorney_fee' => $request->attorney_fee,
                'office_address' => $request->office_address,
                'success_case' => $request->success_case ?? 0,
                'failed_case' => $request->failed_case ?? 0,
                'public_work' => $request->public_work,
                'lat' => $request->lat,
                'lng' => $request->lng,
            ]);
            if ($user->save()) {
                return $this->responseMsg('User profile updated', LawyerResource::make($user));
            } else {
                return $this->responseMsg('User profile update failed', null, 500);
            }
        } catch (Exception $e) {
            return $this->responseMsg('User profile update failed', null, 500);
        }
    }

    public function lawyerWorkTimes($request)
    {
        try {
            $lawyer_id = Auth::guard('lawyer_api')->id();
            $validator = $this->apiValidator($request->all(), [
//                'lawyer_id' => 'required|exists:lawyers,id',
                'day' => 'required|array',
                'day.*' => 'required|string|in:' . implode(',', WeekDaysEnum::values()) . '|distinct',
                'from' => 'required|array',
                'from.*' => 'required|date_format:H:i|before:to.*',
                'to' => 'required|array',
                'to.*' => 'required|date_format:H:i|after:from.*',
                'status' => 'required|array',
                'status.*' => 'required|string|in:active,inactive'
            ]);
            if ($validator) {
                return $validator;
            }
            $oldLawyerTimes = $this->lawyerTimeService->model->where('lawyer_id', $lawyer_id)->get();
            if ($oldLawyerTimes->count() > 0) {
                foreach ($oldLawyerTimes as $oldLawyerTime) {
                    $oldLawyerTime->forceDelete();
                }
            }
            for ($i = 0; $i < count($request->from); $i++) {
                if (isset($request->from[$i], $request->to[$i], $request->day[$i], $request->status[$i])) {
                    $lawyerWorkTime = $this->lawyerTimeService->model->create(
                        [
                            'lawyer_id' => $lawyer_id,
                            'from' => $request->from[$i],
                            'to' => $request->to[$i],
                            'day' => $request->day[$i],
                            'status' => $request->status[$i]
                        ]);

                    $lawyerWorkTimes[] = $lawyerWorkTime;

//                    dd($lawyerWorkTimes->id);
                } else {
                    return $this->responseMsg('there is a missing parameter');
                }
            }
            return $this->responseMsg('lawyer work times has been added successfully', LawyerTimeResource::collection($lawyerWorkTimes), 201);
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }

    public function updateLawyerWorkTimesStatus($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'day' => 'required|in:' . implode(',', WeekDaysEnum::values()),
                'status' => 'required|string|in:active,inactive'
            ]);
            if ($validator) {
                return $validator;
            }
            $lawyer = $this->model->find(Auth::guard('lawyer_api')->id());

            $oldLawyerTimes = $this->lawyerTimeService->model->where('lawyer_id', $lawyer->id)->where('day', $request->day)->first();
            if ($oldLawyerTimes) {
                $oldLawyerTimes->update(
                    [
                        'status' => $request->status
                    ]);
            }
            return $this->responseMsg('lawyer work times status has been updated successfully', $oldLawyerTimes, 200);
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getLawyerWorkTimes()
    {
        try {
            $user = $this->model->with('lawyerTimes')->find(Auth::guard('lawyer_api')->id());
//            dd($user->lawyerTimes);
            return $this->responseMsg('lawyer work times status has returned successfully', LawyerTimeResource::collection($user->lawyerTimes), 200);
        } catch (Exception $e) {
            return $this->errorResponse();
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
                'email' => 'required|email|unique:lawyers,email,' . Auth::guard('lawyer_api')->id(),
            ]);

            if ($validator) {
                return $validator;
            }

            $user = $this->model->find(Auth::guard('lawyer_api')->id());
            $user->email = $request->email;

            if ($user->save()) {
                return $this->responseMsg('User email updated', LawyerResource::make($user));
            } else {
                return $this->responseMsg('User email update failed', null, 500);
            }
        } catch (Exception $e) {
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
                'email' => 'required|email|exists:lawyers,email',
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
        } catch (Exception $e) {
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
            $user = Auth::guard('lawyer_api')->user();
            $device = DeviceToken::where('user_id', $user->id)
                ->where('user_type', 'lawyer')
                ->where('mac_address', $requset->mac_address)
                ->first();

            if ($device) {
                $device->delete();
            }

            Auth::guard('lawyer_api')->logout();
            return $this->responseMsg('Logout successful', null);
        } catch (Exception $e) {
            return $this->responseMsg('Logout failed', null, 500);
        }
    }
}
