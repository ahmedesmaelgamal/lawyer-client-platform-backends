<?php

namespace App\Services\Api\Client;

use App\Enums\SosRequestStatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Requests\CommunitySubCategoryRequest;
use App\Http\Resources\CommunityServiceCategoryResource;
use App\Http\Resources\CommunityServiceResource;
use App\Http\Resources\CommunityServiceSubCategoryResource;
use App\Http\Resources\ContractCategoryResource;
use App\Http\Resources\ContractFileResource;
use App\Http\Resources\ContractResource;
use App\Http\Resources\LawyerAdResource;
use App\Http\Resources\LawyerResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\PointTransactionResource;
use App\Http\Resources\SosRequestResource;
use App\Models\Client as ObjModel;
use App\Models\ClientPoint;
use App\Models\CommunityCategory;
use App\Models\CommunityService;
use App\Models\CommunitySubCategory;
use App\Models\ContractCategory;
use App\Models\ContractFile;
use App\Models\PointTransaction;
use App\Models\Setting;
use App\Models\SOSRequest;
use App\Models\WalletTransaction;
use App\Services\Admin\AdService;
use App\Services\Admin\CommunityCategoryService;
use App\Services\Admin\LawyerService;
use App\Services\BaseService;
use App\Services\PaymobService;
use App\Traits\FirebaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

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
        protected AdService            $adService,
        protected LawyerService        $lawyerService,
        protected SOSRequest           $sosRequest,
        protected CommunityCategory    $communityCategory,
        protected CommunityService     $communityService,
        protected CommunitySubCategory $communitySubCategory,
        protected ContractCategory     $contractCategory,
        protected ContractFile         $contractFile,
        protected ClientPoint          $clientPoint,
        protected Setting              $settings,
        protected PointTransaction     $pointTransaction,
        protected PaymobService $paymobService,
        protected WalletTransaction $walletTransaction,
        protected Setting $setting,
    ) {
        parent::__construct($model);
    }

    public function home($request)
    {
        try {
            $latitude = $request->lat;
            $longitude = $request->lng;

            $validator = $this->apiValidator($request->all(), [
                'lat' => 'required',
                'lng' => 'required',
            ]);

            if ($validator) {
                return $validator;
            }

            // Get the latest lawyers ordered by their average rate
            $topRateLawyers = $this->getTopRateLawyers($request);

            $lawyerOffers = $this->adService->model->active()->latest()->get();

            $nearLawyers = $this->getNearLawyers($latitude, $longitude);

            $data = [
                'lawyerOffers' => LawyerAdResource::collection($lawyerOffers),
                'topRateLawyers' => LawyerResource::collection($topRateLawyers->take(8)),
                'nearLawyers' => LawyerResource::collection($nearLawyers->take(8)),
            ];

            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getLawyerOffers()
    {
        $lawyerOffers = $this->adService->model->active()->latest()->get();
        $data = [
            'lawyerOffers' => LawyerAdResource::collection($lawyerOffers),
        ];

        return $this->successResponse($data);
    }

    /**
     * Get lawyers based on various filters and sorting options
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLawyers($request)
    {
        try {
            // Validate the request
            $validator = $this->apiValidator($request->all(), [
                'type' => 'nullable|in:near,top',
                'level_id' => 'nullable',
                'speciality_ids' => 'nullable',
                'lat' => 'nullable',
                'lng' => 'nullable',
                'top_rated_asc' => 'nullable',
                'top_rated_desc' => 'nullable',
                'near_asc' => 'nullable',
                'near_desc' => 'nullable',
                'price_asc' => 'nullable',
                'price_desc' => 'nullable'
            ]);

            // Return validation errors if any
            if ($validator) {
                return $validator;
            }

            // Initialize the query
            $lawyersQuery = $this->lawyerService->model->active();

            // Apply filters based on request parameters
            if ($request->has('type')) {
                if ($request->type == 'top') {
                    // Get top-rated lawyers
                    $lawyersQuery = $this->getTopRateLawyersQuery($request);
                } elseif ($request->type == 'near') {
                    // Get nearby lawyers
                    $lawyersQuery = $this->getNearLawyersQuery($request->lat, $request->lng, $request->level_id);
                }
            } else {
                // Apply level_id filter
                if ($request->level_id) {
                    $lawyersQuery->where('level_id', $request->level_id);
                }

                // Apply speciality_ids filter
                if ($request->speciality_ids) {
                    $lawyersQuery->whereHas('lawyerSpecialities', function ($query) use ($request) {
                        $query->whereIn('speciality_id', explode(',', $request->speciality_ids));
                    });
                }

                // Apply sorting
                if ($request->top_rated_asc) {
                    $lawyersQuery->withAvg('rates as avg_rate', 'rate')->orderBy('avg_rate', 'asc');
                } elseif ($request->top_rated_desc) {
                    $lawyersQuery->withAvg('rates as avg_rate', 'rate')->orderBy('avg_rate', 'desc');
                } elseif ($request->near_asc) {
                    $lawyersQuery->with('lawyerAbout')
                        ->selectRaw(
                            'lawyers.*, (6371 * acos(cos(radians(?)) * cos(radians(lawyer_abouts.lat)) * cos(radians(lawyer_abouts.lng) - radians(?)) + sin(radians(?)) * sin(radians(lawyer_abouts.lat)))) AS distance',
                            [$request->lat, $request->lng, $request->lat]
                        )
                        ->join('lawyer_abouts', 'lawyers.id', '=', 'lawyer_abouts.lawyer_id')
                        ->orderBy('distance', 'asc');
                } elseif ($request->near_desc) {
                    $lawyersQuery->with('lawyerAbout')
                        ->selectRaw(
                            'lawyers.*, (6371 * acos(cos(radians(?)) * cos(radians(lawyer_abouts.lat)) * cos(radians(lawyer_abouts.lng) - radians(?)) + sin(radians(?)) * sin(radians(lawyer_abouts.lat)))) AS distance',
                            [$request->lat, $request->lng, $request->lat]
                        )
                        ->join('lawyer_abouts', 'lawyers.id', '=', 'lawyer_abouts.lawyer_id')
                        ->orderBy('distance', 'desc');
                } elseif ($request->price_desc) {
                    $lawyersQuery
                        ->join('lawyer_abouts', 'lawyers.id', '=', 'lawyer_abouts.lawyer_id')
                        ->orderBy('lawyer_abouts.attorney_fee', 'desc')
                        ->select('lawyers.*'); // to avoid column collision
                } elseif ($request->price_asc) {
                    $lawyersQuery
                        ->join('lawyer_abouts', 'lawyers.id', '=', 'lawyer_abouts.lawyer_id')
                        ->orderBy('lawyer_abouts.attorney_fee', 'asc')
                        ->select('lawyers.*');
                }
            }

            // Execute the query and get the results
            $lawyers = $lawyersQuery->get();

            // Return the response
            return $this->successResponse(LawyerResource::collection($lawyers));
        } catch (\Exception $e) {
            // Log the error and return a generic error response
            return $this->errorResponse();
        }
    }

    protected function getNearLawyers($latitude, $longitude, $level_id = null)
    {
        $query = $this->lawyerService->model
            ->active()
            ->when($level_id, function ($query) use ($level_id) {
                return $query->where('level_id', $level_id);
            })
            ->with('lawyerAbout')
            ->selectRaw(
                'lawyers.*, (6371 * acos(cos(radians(?)) * cos(radians(lawyer_abouts.lat)) * cos(radians(lawyer_abouts.lng) - radians(?)) + sin(radians(?)) * sin(radians(lawyer_abouts.lat)))) AS distance',
                [$latitude, $longitude, $latitude]
            )
            ->join('lawyer_abouts', 'lawyers.id', '=', 'lawyer_abouts.lawyer_id')
            ->orderBy('distance', 'asc');

        return $query->get();
    }

    protected function getTopRateLawyers($request)
    {
        $query = $this->lawyerService->model
            ->active()
            ->when($request->level_id, function ($query) use ($request) {
                return $query->where('level_id', $request->level_id);
            })
            ->when($request->speciality_id, function ($query) use ($request) {
                return $query->whereHas('lawyerSpecialities', function ($query) use ($request) {
                    $query->where('speciality_id', $request->speciality_id);
                });
            })
            ->withAvg('rates as avg_rate', 'rate')
            ->orderBy('avg_rate', 'desc')
            ->latest()
            ->get();

        return $query;
    }

    protected function getTopRateLawyersQuery($request)
    {
        $query = $this->lawyerService->model
            ->active()
            ->when($request->level_id, function ($query) use ($request) {
                return $query->where('level_id', $request->level_id);
            })
            ->when($request->speciality_id, function ($query) use ($request) {
                return $query->whereHas('lawyerSpecialities', function ($query) use ($request) {
                    $query->where('speciality_id', $request->speciality_id);
                });
            })
            ->withAvg('rates as avg_rate', 'rate')
            ->orderBy('avg_rate', 'desc')
            ->latest();

        return $query;
    }

    protected function getNearLawyersQuery($latitude, $longitude, $level_id = null)
    {
        $query = $this->lawyerService->model
            ->active()
            ->when($level_id, function ($query) use ($level_id) {
                return $query->where('level_id', $level_id);
            })
            ->with('lawyerAbout')
            ->selectRaw(
                'lawyers.*, (6371 * acos(cos(radians(?)) * cos(radians(lawyer_abouts.lat)) * cos(radians(lawyer_abouts.lng) - radians(?)) + sin(radians(?)) * sin(radians(lawyer_abouts.lat)))) AS distance',
                [$latitude, $longitude, $latitude]
            )
            ->join('lawyer_abouts', 'lawyers.id', '=', 'lawyer_abouts.lawyer_id')
            ->orderBy('distance', 'asc');

        return $query;
    }

    public function addSosRequest($request)
    {
        try {
            $user = $this->model->find(Auth::guard('client_api')->user()->id);
            $validator = $this->apiValidator($request->all(), [
                'problem' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'lat' => 'required',
                'long' => 'required',
                'voice' => 'nullable|file',
                'lawyer_id' => 'nullable|exists:lawyers,id'
            ]);

            if ($validator) {
                return $validator;
            }

            $newSos = new $this->sosRequest();
            $newSos->problem = $request->problem;
            $newSos->phone = $request->phone;
            $newSos->address = $request->address;
            $newSos->lat = $request->lat;
            $newSos->long = $request->long;
            $newSos->client_id = $user->id;
            $newSos->status = SosRequestStatusEnum::NEW->value;
            $newSos->lawyer_id = $request->lawyer_id;


            //upload voice file
            if ($request->hasFile('voice')) {
                $newSos->voice = $this->handleFile($request->file('voice'), 'sos-voices', 'file');
            }

            // Save the new SOS request
            if ($newSos->save()) {
                $data = [
                    'title' => 'SOS request',
                    'body' => 'you have a new sos request',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'court_case_sos',
                    'model' => 'sos_request',
                    'model_id' => $newSos->id,
                ];
                $this->sendFcm($data, [$newSos->lawyer_id], 'lawyer_api');
                return $this->responseMsg('SOS request added successfully', new SosRequestResource($newSos));
            }

            return $this->errorResponse();
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function sosLawyers($request)
    {
        try {
            $user = $this->model->find(Auth::guard('client_api')->user()->id);
            $lawyersIds = [];
            foreach ($user->courtCases as $courtCase) {
                $lawyersIds[] = $courtCase->acceptedEvent ? $courtCase->acceptedEvent->lawyer_id : null;
            }
            $myLawyers = $this->lawyerService->model->whereIn('id', $lawyersIds)->get();
            $latitude = $request->lat;
            $longitude = $request->lng;

            $validator = $this->apiValidator($request->all(), [
                'lat' => 'required',
                'lng' => 'required',
            ]);

            if ($validator) {
                return $validator;
            }

            $nearLawyers = $this->getNearLawyers($latitude, $longitude);

            $data['nearLawyers'] = LawyerResource::collection($nearLawyers->take(8));
            $data['myLawyers'] = LawyerResource::collection($myLawyers);
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getCommunityServiceCategories()
    {
        try {
            $categories = $this->communityCategory->active()->when(request('q'), function ($query) {
                $searchTerm = '%' . request('q') . '%';
                $query->where('title', 'like', $searchTerm)
                    ->orWhereHas('CommunitySubCategories', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('title', 'like', $searchTerm);
                    });
            })->get();

            return $this->successResponse(CommunityServiceCategoryResource::collection($categories));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function getCommunityServiceSubCategories($id)
    {
        try {
            $subCategories = $this->communitySubCategory->where('community_category_id', $id)->active()
                ->when(request('q'), function ($query) {
                    $query->where('title', 'like', '%' . request('q') . '%');
                })->get();
            return $this->successResponse(CommunityServiceSubCategoryResource::collection($subCategories));
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getCommunityService($id)
    {
        try {
            $communityService = $this->communityService->where('community_sub_category_id', $id)->active()->get();
            return $this->successResponse(CommunityServiceResource::collection($communityService));
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function addClientPoints($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'code' => 'required',
            ]);
            if ($validator) {
                return $validator;
            }
            $codeSender = $this->clientPoint->whereCommercialCode($request->code)->first();
            if ($codeSender == null) {
                return $this->errorResponse('the user with the associated commercial code has not been found');
            }
            $sender = $this->model->where('id', $codeSender->client_id)->first();
            $receiver = auth('client_api')->user();
            $codeReceiver = $this->clientPoint->where('client_id', $receiver->id)->first();
            $senderPoints = $this->settings->where('key', 'referral_sender_points')->first()->value;
            $receiverPoints = $this->settings->where('key', 'referral_receiver_points')->first()->value;
            if ($codeReceiver == $codeSender) {
                return $this->errorResponse('you can not use your own commercial code to register');
            }
            if ($codeSender) {
                if ($codeReceiver->entered_with_code == null) {
                    $codeSender->update([
                        'points' => $codeSender->points + $senderPoints,
                    ]);
                    $codeReceiver->update([
                        'points' => $codeReceiver->points + $receiverPoints,
                        'entered_with_code' => $request->code
                    ]);
                    $sender->update([
                        'points' => $sender->points + $senderPoints,
                    ]);
                    $receiver->update([
                        'points' => $sender->points + $receiverPoints,
                    ]);

                    $data1 = [
                        'title' => 'commercial points',
                        'body' => 'you have entered a valid commercial code and received ' . $receiverPoints . ' points',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'commercial_points_receiver',
                        //                        'model' => 'court_cases',
                        //                        'module_id' => $courtCase->id,
                    ];
                    $this->sendFcm($data1, [$receiver->id], 'client_api');

                    $data2 = [
                        'title' => 'commercial points',
                        'body' => 'your commercial code has been used and you have received ' . $senderPoints . ' points',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'commercial_points_sender',
                        //                        'model' => 'court_cases',
                        //                        'module_id' => $courtCase->id,
                    ];
                    $this->sendFcm($data2, [$sender->id], 'client_api');


                    return $this->successResponse('the commercial code has been registered successfully and the point has been added');
                } else {
                    return $this->errorResponse('You have already registered with a code');
                }
            } else {
                return $this->errorResponse('the user with the associated commercial code has not been found');
            }
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function pointTransaction($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'cash' => 'required|numeric|min:1',
            ]);
            if ($validator) {
                return $validator;
            }
            $client = auth('client_api')->user();
            $pointToCashAmount = $this->settings->where('key', 'point_to_cash')->first()->value;
            if ($request->cash > $client->points / $pointToCashAmount) {
                return $this->errorResponse('you do not have enough points');
            } else {
                $client->update([
                    'points' => $client->points - $request->cash * $pointToCashAmount,
                    'wallet' => $client->wallet + $request->cash,
                ]);
                $this->pointTransaction->create([
                    'client_id' => $client->id,
                    'points' => $request->cash * $pointToCashAmount,
                    'comment' => 'من النقاط ' . $pointToCashAmount . ' تم إستخدام',
                ]);

                $data = [
                    'title' => 'point conversion',
                    'body' => $pointToCashAmount . ' points have been converted to cash successfully',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'point_to_cash',
                    //                        'model' => 'court_cases',
                    //                        'module_id' => $courtCase->id,
                ];
                $this->sendFcm($data, [$client->id], 'client_api');


                return $this->successResponse('the points have been converted to cash successfully');
            }
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }


    public function getCommercialCode()
    {
        try {
            $code = $this->clientPoint->where('client_id', auth('client_api')->user()->id)->first()->commercial_code;
            return $this->successResponse($code);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClientPoint()
    {
        try {
            $client = auth('client_api')->user();
            $data['points'] = $client->points;
            $data['cash'] = number_format($client->points / $this->settings->where('key', 'point_to_cash')->first()->value, 2, '.', '');
            $data['transactions'] = PointTransactionResource::collection($this->pointTransaction->where('client_id', $client->id)->latest()->get());
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }


    public function getContractFiles($request)
    {
        $validator = $this->apiValidator($request->all(), [
            'contract_category_id' => 'required|exists:contract_categories,id',
        ]);

        if ($validator) {
            return $validator;
        }
        try {
            $contractFile = $this->contractFile->where('contract_category_id', $request->contract_category_id)->get();
            return $this->successResponse(ContractFileResource::collection($contractFile));
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getContractCategories()
    {

        try {

            return $this->successResponse(ContractCategoryResource::collection($this->contractCategory->get()));
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }


    public function getContracts()
    {
        try {
            $searchTerm = request('q') ? '%' . request('q') . '%' : null;

            $contracts = $this->contractCategory
                ->when($searchTerm, function ($query) use ($searchTerm) {
                    $query->where(function ($q) use ($searchTerm) {
                        $q->where('title', 'like', $searchTerm)
                            ->orWhereHas('contractFiles', function ($subQuery) use ($searchTerm) {
                                $subQuery->where('file_name', 'like', $searchTerm);
                            });
                    });
                })
                ->get();

            return $this->successResponse(ContractResource::collection($contracts));
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function contractFileCheckout($request)
    {
        $validator = $this->apiValidator($request->all(), [
            'contract_file_id' => 'required|exists:contract_files,id',
        ]);


        if ($validator) {
            return $validator;
        }


        try {
            $client = auth('client_api')->user();
            if(!$client){
                return $this->errorResponse('Client not found', null, 401);
            }
         

            $contractFile = $this->contractFile->find($request->contract_file_id);
            $filePrice = $this->setting->where("key" , "filePrice")->first()->value ?? 1;
            if ($client->wallet < $filePrice) {
                return $this->responseMsg('you do not have enough balance in your wallet', null, 400);
            }



            $ifPurchased = $this->walletTransaction->where("model_id" , $contractFile->id)
            ->where("model_type" , ContractFile::class)
            ->where("user_id" , $client->id)
            ->where("user_type" , UserTypeEnum::CLIENT->value)
            ->exists();

            if ($ifPurchased) {
                return $this->responseMsg('you have already purchased this file', null, 400);
            }
            
            DB::beginTransaction();
                $client->update([
                    'wallet' =>  $client->wallet - $filePrice,
                ]);

                
            
                $this->walletTransaction->create([
                    'user_id' => $client->id,
                    'user_type' => UserTypeEnum::CLIENT->value,
                    "debit" => $filePrice,
                    'credit' => 0,
                    "model_id" => $contractFile->id,
                    "model_type" => ContractFile::class,
                    'comment' => 'Contract file downloaded',
                ]);
            DB::commit();

            return $this->responseMsg('data fetched ',getFile($contractFile->file_path),200);

        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getWalletTransactions()
    {
        try {
            $user = auth('client_api')->user();
            $walletTransactions = $user->walletTransactions;
            $data['wallet'] = $user->wallet ?? '0';
            $data['walletTransactions'] = $walletTransactions;
            $date["walletTransactions"]["user_id"] = (int)$user->id;
            return $this->responseMsg('wallet get successfully', $data);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getNotifications()
    {
        try {
            $user = auth('client_api')->user();
            $user->notifications()->update(['seen' => 1]);
            $notifications = NotificationResource::collection($user->notifications);
            return $this->successResponse($notifications);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }


    public function withdrawRequest($request)
    {
        $lawyer = auth('client_api')->user();
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
}
