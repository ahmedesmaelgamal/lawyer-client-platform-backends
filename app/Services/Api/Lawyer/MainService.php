<?php

namespace App\Services\Api\Lawyer;

use App\Enums\LawyerStatusEnum;
use App\Enums\OfficeRequestEnum;
use App\Enums\StatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Resources\CourtCaseResource;
use App\Http\Resources\LawyerResource;
use App\Http\Resources\MarketOfferResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\OfficeRequestResource;
use App\Models\WalletTransaction;
use App\Models\WithdrawRequest;
use App\Services\Admin\LevelService;
use App\Services\Admin\MarketProductService;
use App\Services\Admin\OrderService;
use App\Services\Api\Client\LawyerService;
use App\Services\BaseService;
use App\Models\Lawyer as ObjModel;
use App\Models\OfficeRequest;
use App\Services\Admin\CityService;
use App\Services\Admin\CountryService;
use App\Services\Admin\MarketOfferService;
use App\Services\Admin\RefuseReasonService;
use App\Services\Admin\SpecialityService;
use App\Traits\FirebaseNotification;
use Illuminate\Http\JsonResponse;

class MainService extends BaseService
{

    use FirebaseNotification;

    /**
     * Constructor method
     *
     * @param ObjModel $model
     *
     * @return void
     */
    public function __construct(
        ObjModel                       $model,
        protected WalletTransaction    $walletTransaction,
        protected MarketOfferService   $marketOfferService,
        public LawyerService           $lawyerService,
        protected CourtCaseService     $courtCaseService,
        protected SpecialityService    $specialityService,
        protected CountryService       $countryService,
        protected CityService          $cityService,
        public LevelService            $levelService,
        protected RefuseReasonService  $refuseReasonServic,
        protected MarketProductService $marketProductService,
        protected OfficeRequest        $officeRequest,
        protected OrderService         $orderService,
        protected WithdrawRequest      $withdrawRequest,
    )
    {

        parent::__construct($model);
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function home($request)
    {
        // try {
            $lawyer = $this->lawyerService->model->where('id', auth('lawyer_api')->user()->id)->first();
            $data['marketOffers'] = MarketOfferResource::collection($this->marketOfferService->getLatestActiveOffers());
            $data['newCourtCases'] = CourtCaseResource::collection($this->courtCaseService->newCourtCases($request));
            $data['lawyerData'] = LawyerResource::make($this->lawyerService->lawyerDetailsResponse($lawyer->id));
            $data['officeRequests'] = $this->getOffice();

            return $this->successResponse($data);

        // } catch (\Exception $e) {
        //     return $this->errorResponse();
        // }
    }

    public function getOffice()
    {

        $lawyer = auth('lawyer_api')->user();
        if ($lawyer->type == LawyerStatusEnum::INDIVIDUAL->value) {
            $officeRequests = OfficeRequestResource::collection($this->officeRequest->where('lawyer_id', $lawyer->id)
                ->where('status', (string)OfficeRequestEnum::NEW->value)
                ->get());
            return $officeRequests;
        } else {
            return [];
        }
    }

    public function getOfficeRequest()
    {
        if ($this->getOffice()->count() > 0) {
            return $this->responseMsg('office requests returned successfully', $this->getOffice(), 200);

        } else {
            return $this->responseMsg('no office requests available', null, 200);
        }
    }

    public function sendOfficeResponse($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'request_id' => 'required',
                'status' => 'required|in:1,2',
            ], [
                'lawyer_id.required' => 'Lawyer id is required',
                'status.required' => 'status is required',
            ]);

            if ($validator) {
                return $validator;
            }
            $lawyer = auth('lawyer_api')->user();
            $officeRequest = $this->officeRequest->where('lawyer_id', $lawyer->id)->where('id', $request->request_id);
            if ($lawyer->type == LawyerStatusEnum::OFFICE->value) {
                return $this->responseMsg('permission denied, you are already an office', null, 422);
            }
            if ($officeRequest->exists() && $officeRequest->first()->status == OfficeRequestEnum::NEW->value && $officeRequest->first()->lawyer_id == $lawyer->id) {
                $officeRequest->update(
                    [
                        'status' => $request->status
                    ]);
                $lawyer->update(
                    [
                        'office_id' => $officeRequest->first()->office_id
                    ]);
                if ($request->status == OfficeRequestEnum::ACCEPTED->value) {
                    $data = [
                        'title' => 'office request accepted',
                        'body' => 'the lawyer has accepted your office request',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'office_response',
                    ];
                    $this->sendFcm($data, [$lawyer->office_id], 'lawyer_api');

                }
                if ($request->status == OfficeRequestEnum::REJECTED->value) {
                    $data = [
                        'title' => 'office request rejected',
                        'body' => 'the lawyer has rejected your office request',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'office_response',
                    ];
                    $this->sendFcm($data, [$lawyer->office_id], 'lawyer_api');

                }

                return $this->responseMsg('the request has been updated successfully', null, 200);
            } else {
                return $this->responseMsg('there is no new request with this id', null, 422);
            }
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }


    public function deleteLawyerFromOffice($request)
    {// the office
//        try {

        $validator = $this->apiValidator($request->all(), [
            'id' => 'required|exists:lawyers,id',
        ]);
        if ($validator) {
            return $validator;
        }
        $office = auth('lawyer_api')->user();
        if ($office->office_id != null) {
            return $this->responseMsg("you are not an office", null, 422);
        }
        $lawyer = $this->lawyerService->model->where('id', $request->id)->first();
        if (!$lawyer){
            return $this->responseMsg('the lawyer you want to delete from this office does not exit', null, 200);
        }
        if ($office->id != $lawyer->office_id || $lawyer->type == LawyerStatusEnum::OFFICE->value || $lawyer->status == StatusEnum::INACTIVE->value) {
            return $this->responseMsg("this lawyer isn't registered in your office", null, 422);
        } else {
            $lawyer->update(['office_id' => null]);
            $lawyer->save();
            return $this->responseMsg('the lawyer has been deleted from the office successfully', null, 200);
        }
//        } catch (\Exception $e) {
//            return $this->errorResponse();
//        }
    }

    public function deleteLawyerFromOfficeRequest()
    {//the lawyer
        try {
            $lawyer = auth('lawyer_api')->user();
            if ($lawyer->office_id == null) {
                return $this->responseMsg("you are not registered in any office", null, 422);
            } elseif ($lawyer->status == StatusEnum::INACTIVE->value) {
                return $this->responseMsg("you must be activated before you send delete request to your office", null, 422);
            } elseif ($this->lawyerService->model->where('id', $lawyer->office_id)->exists()) {
                return $this->responseMsg("the office you are registered in doesn't exist", null, 422);
            } else {
                $data = [
                    'title' => 'office delete request',
                    'body' => 'the lawyer ' . $lawyer->name . ' has sent a delete request to the office',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'office_delete_request',
                ];
                $this->sendFcm($data, [$lawyer->office_id], 'lawyer_api');
                return $this->responseMsg("the delete request have been sent successfully to the office", null, 200);
            }
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }


    public function deleteLawyerFromOfficeResponse($request)
    {//the lawyer
//        try {
        $validator = $this->apiValidator($request->all(), [
            'id' => 'required|exists:lawyers,id',
            'status' => 'required|in:1,2'
        ], [
            'id.required' => 'Lawyer id is required',
            'status.required' => 'status is required',
        ]);

        if ($validator) {
            return $validator;
        }

        $office = auth('lawyer_api')->user();
        if ($office->type == LawyerStatusEnum::INDIVIDUAL->value) {
            return $this->responseMsg('permission denied, you are not an office', null, 422);
        }
        $lawyer = $this->lawyerService->model->where('id', $request->id)->first();
        if (!$lawyer) {
            return $this->responseMsg('the lawyer you want to delete from this office does not exit', null, 200);
        }
//            dd($lawyer->office_id);
        if ($office->id != $lawyer->office_id || $lawyer->type == LawyerStatusEnum::OFFICE->value || $lawyer->status == StatusEnum::INACTIVE->value) {
            return $this->responseMsg("this lawyer isn't registered in your office", null, 422);
        }

        if ($request->status == 1) {
            $lawyer->update(['office_id' => null]);
            $lawyer->save();
            $data = [
                'title' => 'deleting from office',
                'body' => 'the office ' . $office->name . ' has accepted your delete request',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type' => 'office_delete_request',
            ];
            $this->sendFcm($data, [$lawyer->id], 'lawyer_api');
            return $this->responseMsg('the lawyer has been deleted from the office successfully', null, 200);
        } elseif ($request->status == 2) {
            $lawyer->update(['office_id' => null]);
            $lawyer->save();
            $data = [
                'title' => 'deleting from office',
                'body' => 'the office ' . $office->name . ' has not accepted your delete request',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type' => 'office_delete_request',
            ];
            $this->sendFcm($data, [$lawyer->id], 'lawyer_api');
            return $this->responseMsg('the lawyer has been deleted from the office successfully', null, 200);
        } else {
            return $this->responseMsg('the status you have entered is not valid', null, 422);
        }

//        } catch (\Exception $e) {
//            return $this->errorResponse();
//        }
    }

    public function getWalletTransactions()
    {
        $lawyer = auth('lawyer_api')->user();
        $walletTransactions = $lawyer->walletTransactions;
        $data['wallet'] = $lawyer->wallet ?? '0';
        $data['walletTransactions'] = $walletTransactions;
        return $this->responseMsg('wallet get successfully', $data);
    }

    public function withdrawRequest($request)
    {
        $lawyer = auth('lawyer_api')->user();
        $validator = $this->apiValidator(request()->all(), [
            'amount' => 'required|numeric|min:1|max:' . $lawyer->wallet,
            'payment_method' => 'required|in:bank,ipa,wallet',
            'payment_key' => 'required|string',
        ], [
            'amount.required' => 'amount is required',
            'amount.numeric' => 'amount should be numeric',
            'amount.min' => 'minimum amount is 1',
            'amount.max' => 'maximum amount is ' . $lawyer->wallet,
        ]);

        if ($validator) {
            return $validator;
        }

        $checkPendingRequest = $this->withdrawRequest->where('user_id', $lawyer->id)
            ->where('user_type', UserTypeEnum::LAWYER->value)
            ->where('status', 'pending')
            ->exists();
        if ($checkPendingRequest) {
            return $this->responseMsg('you have a pending request please wait', null, 422);
        }

        $addWithdrawRequest = new $this->withdrawRequest();
        $addWithdrawRequest->user_id = $lawyer->id;
        $addWithdrawRequest->user_type = UserTypeEnum::LAWYER->value;
        $addWithdrawRequest->amount = $request->amount;
        $addWithdrawRequest->status = 'pending';
        $addWithdrawRequest->payment_method = $request->payment_method;
        $addWithdrawRequest->payment_key = $request->payment_key;
        $addWithdrawRequest->save();

        return $this->responseMsg('withdraw request has been sent successfully');
    }


    public function getOfficeTeam()
    {
        try {
            $lawyer = auth('lawyer_api')->user();
            if ($lawyer->type == LawyerStatusEnum::INDIVIDUAL->value && !$lawyer->office_id) {
                return $this->responseMsg('No office or Team Work found , try again ', null, 404);
            }
            $officeTeam = ($lawyer->type == LawyerStatusEnum::OFFICE->value) ?
                $this->model->where('office_id', $lawyer->id)->get() : $this->model->where('office_id', $lawyer->office_id)->get();

            $office = ($lawyer->type == LawyerStatusEnum::OFFICE->value) ?
                $this->model->where('id', $lawyer->id)->first() : $this->model->where('id', $lawyer->office_id)->first();

            $data['team'] = LawyerResource::collection($officeTeam);
            $data['office'] = LawyerResource::make($office);
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }


    public function sendOfficeRequest($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'lawyer_id' => 'required',
            ], [
                'lawyer_id.required' => 'Lawyer id is required',
            ]);

            if ($validator) {
                return $validator;
            }

            $office = auth('lawyer_api')->user();

            if ($office->type == LawyerStatusEnum::INDIVIDUAL->value) {
                return $this->responseMsg('permission denied, try again as office', null, 422);
            }

            $lawyer = $this->model->where('lawyer_id', $request->lawyer_id)->first();
            if (!$lawyer) {
                return $this->responseMsg('Lawyer not found', null, 404);
            }
            $lawyerRequest = $lawyer->officeRequest()->where('office_id', $office->id)->where('status', '!=', OfficeRequestEnum::REJECTED->value)->first();

            if ($lawyer->office_id != $office->id) {
                if (!$lawyerRequest) {
                    $this->officeRequest->create([
                        'office_id' => $office->id,
                        'lawyer_id' => $lawyer->id,
                    ]);

                    $data = [
                        'title' => 'new office request',
                        'body' => 'a new office request has been sent to you',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'office_request',
                    ];
                    $this->sendFcm($data, [$request->lawyer_id], 'lawyer_api');

                    return $this->responseMsg('this request has been sent successfully');
                } else {
                    if ($lawyerRequest->status == OfficeRequestEnum::NEW->value) {
                        return $this->responseMsg('A request has already been sent to this user', null, 422);
                    }
                }
            } else {
                return $this->responseMsg('this lawyer is already in this office', 422);
            }
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }


    public function searchLawyer($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'lawyer_id' => 'required|exists:lawyers,lawyer_id',
            ], [
                'lawyer_id.required' => 'Lawyer id is required',
            ]);

            if ($validator) {
                return $validator;
            }

            $lawyer = $this->model->where('lawyer_id', $request->lawyer_id)->first();
            if ($lawyer && $lawyer->type == LawyerStatusEnum::INDIVIDUAL->value) {
                $data = LawyerResource::make($lawyer);
                return $this->successResponse($data);
            } else {
                return $this->responseMsg('Lawyer not found', null, 422);
            }
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function deleteLawyerById($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'id' => 'required|exists:lawyers,id',
            ], [
                'id.required' => 'Lawyer id is required',
            ]);

            if ($validator) {
                return $validator;
            }
            $lawyer = $this->model->where('id', $request->id)->first();
            if ($lawyer) {
                if ($lawyer->type == LawyerStatusEnum::OFFICE->value) {
                    $emptyOffice = $this->lawyerService->model->where('office_id', $lawyer->id)->exists() ? 1 : 0;
                }
                if ($lawyer->type == LawyerStatusEnum::INDIVIDUAL->value || $emptyOffice) {
                    $lawyer->delete();
                    return $this->responseMsg('the lawyer has been deleted successfully', null, 200);
                } else {
                    return $this->responseMsg('the lawyer with this id is an office and has lawyers you should first empty the office', null, 200);
                }
            }
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }


    public function getNotifications()
    {
        try {
            $user = auth('lawyer_api')->user();
            $user->notifications()->update(['seen' => 1]);
            $notifications = NotificationResource::collection($user->notifications);
            return $this->successResponse($notifications);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }

    }

    public function changeType()
    {
//        try {
            $user = auth('lawyer_api')->user();
//            dd($user->office_id);
            if ($user->type == LawyerStatusEnum::INDIVIDUAL->value && $user->office_id != null) {
                return $this->responseMsg('you are currently in ' . $this->lawyerService->model->where('id',$user->office_id)->first()->name . ' office you should first cancel your membership', null, 200);
            } elseif ($user->type == LawyerStatusEnum::INDIVIDUAL->value && $user->office_id == null) {
                $user->update(['type' => LawyerStatusEnum::OFFICE->value]);
                $data = [
                    'title' => 'change type',
                    'body' => 'your type has been changed successfully to ' . $user->type,
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'change_type',
                ];
                $this->sendFcm($data, [$user->id], 'lawyer_api');
                return $this->responseMsg('lawyer type has been changed to ' . $user->type . ' successfully', null, 200);
            } elseif ($user->type == LawyerStatusEnum::OFFICE->value && $user->office_id == null) {
                if ($this->lawyerService->model->where('office_id', $user->id)->exists()) {
                    return $this->responseMsg('you have lawyers, you should first empty the office', null, 200);
                }

                $user->update(['type' => LawyerStatusEnum::INDIVIDUAL->value]);
                $data = [
                    'title' => 'change type',
                    'body' => 'your type has been changed successfully to ' . $user->type,
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'change_type',
                ];
                $this->sendFcm($data, [$user->id], 'lawyer_api');
                return $this->responseMsg('lawyer type has been changed to ' . $user->type . ' successfully', null, 200);
            } else {
                return $this->responseMsg('an unexpected error happened', null, 200);

            }
//        } catch (\Exception $e) {
//            return $this->errorResponse();
//        }
    }


}
