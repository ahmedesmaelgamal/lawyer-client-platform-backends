<?php

namespace App\Services\Api\Lawyer;


use App\Enums\AdConfirmationEnum;
use App\Enums\ExpireEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\StatusEnum;
use App\Http\Resources\AdOfferPackageResource;
use App\Http\Resources\AdResource;
use App\Http\Resources\LawyerPackageResource;
use App\Http\Resources\MarketProductResource;
use App\Http\Resources\OrderResource;
use App\Models\Ad as ObjModel;
use App\Models\WalletTransaction;
use App\Services\Admin\LawyerPackageService;
use App\Services\Admin\OfferPackageService;
use App\Services\Admin\OrderService;
use App\Services\BaseService;
use Carbon\Carbon;
use Mockery\Exception;


class AdService extends BaseService
{
    public function __construct(
        ObjModel                                           $model,
        protected \App\Services\Admin\MarketProductService $marketProductService,
        protected OrderService                             $orderService,
        protected OfferPackageService                      $offerPackageService,
        protected LawyerPackageService                     $lawyerPackageService,
        protected WalletTransaction $walletTransaction,
    ) {
        parent::__construct($model);
    }

    public function addOfferPackageToLawyer($request)
    {
        //
    }


    public function addAdOfferPackage($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'title' => 'required',
                'number_of_days' => 'required|integer',
                'number_of_ads' => 'required|integer',
                'price' => 'required',
                'discount' => 'required',
            ], [
                'title.required' => 'package title is required',
                'number_of_days.required' => 'number of days is required',
                'number_of_ads.required' => 'number of ads is required',
                'price' => 'price is required',
                'discount' => 'the discount is required',
            ]);

            if ($validator) {
                return $validator;
            }
            $adPackage = $this->offerPackageService->model->create([
                'title' => $request->title,
                'number_of_days' => $request->number_of_days,
                'number_of_ads' => $request->number_of_ads,
                'price' => $request->price,
                'discount' => $request->discount,
                'status' => StatusEnum::ACTIVE
            ]);
            return $this->responseMsg('the ad offer package has been created successfully', AdOfferPackageResource::make($adPackage), 201);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function addAdToLawyerPackage($request)
    {
        // try {
            $validator = $this->apiValidator($request->all(), [
                'id' => 'required|exists:lawyer_packages,id',
                'from_date' => 'required|date|date_format:Y-m-d|before:to_date|after:today',
                'to_date' => 'required|date|date_format:Y-m-d|after:from_date',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validator) {
                return $validator;
            }

            

            $lawyer = auth('lawyer_api')->user();
            $lawyerPackage = $this->lawyerPackageService->model->where('id', $request->id)->first();
            if (!$lawyerPackage) {
                return $this->responseMsg('Lawyer package not found', null, 404);
            }

            $offerPackage = $this->offerPackageService->model->where('id', $lawyerPackage->package_id)->first();
            if (!$offerPackage) {
                return $this->responseMsg('Offer package not found', null, 404);
            }

            $lawyerPackageEndDate = Carbon::parse($request->from_date)->addDays($offerPackage->number_of_days);
            if (Carbon::parse($request->to_date)->greaterThan($lawyerPackageEndDate)) {
                return $this->responseMsg('The end date must be before ' . $lawyerPackageEndDate->format('Y-m-d'), null, 422);
            }

            if ($lawyerPackage->status !== StatusEnum::ACTIVE->value) {
                return $this->responseMsg('This package is not active', null, 422);
            }

            if ($lawyerPackage->is_expired !== ExpireEnum::ONGOING->value) {
                return $this->responseMsg('This package is expired', null, 422);
            }

            if ($offerPackage->number_of_ads <= $lawyerPackage->number_of_bumps) {
                $lawyerPackage->status = StatusEnum::INACTIVE->value;
                $lawyerPackage->is_expired = ExpireEnum::EXPIRED->value;
                $lawyerPackage->save();
                return $this->responseMsg('You have reached the maximum number of ads for this package', null, 422);
            }

            $lawyerPackage->number_of_bumps += 1;
            
            $lawyerPackage->save();
            if ($request->hasFile('image')) {
                $imagePath = $this->saveImage($request->file('image'), 'uploads/images');
            } else {
                $imagePath = null; 
            }

            $this->model->create([
                'lawyer_id' => $lawyer->id,
                'package_id' => $request->id,
                'status' => StatusEnum::ACTIVE->value,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'image' => $imagePath,
                'ad_confirmation' => AdConfirmationEnum::REQUESTED->value,
            ]);
            return $this->responseMsg('The ad has been created and added to the lawyer package successfully', null, 200);

        // } catch (\Exception $e) {
        //     return $this->errorResponse();
        // }
    }


    public function addAdOfferPackageToLawyer($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'package_id' => 'required|exists:offer_packages,id',
            ]);

            if ($validator) {
                return $validator;
            }
            $lawyer = auth('lawyer_api')->user();
            $offerPackage = $this->offerPackageService->model->where('id', $request->package_id)->first();

            if ($lawyer->wallet < $offerPackage->price) {
                return $this->responseMsg('the lawyer wallet is not enough to add this package', null, 422);
            }

            if ($offerPackage) {
                $offerStartDate = Carbon::now();
                $newEndDate = Carbon::parse($offerStartDate)->addDays($offerPackage->number_of_days);
                $lawyerPackage = $this->lawyerPackageService->model->create([
                    'lawyer_id' => $lawyer->id,
                    'package_id' => $request->package_id,
                    'start_date' => $offerStartDate->format('Y-m-d'),
                    'end_date' => $newEndDate->format('Y-m-d'),
                    'number_of_bumps' => 0,
                    'is_expired' => ExpireEnum::ONGOING->value,
                    'status' => StatusEnum::ACTIVE->value,
                ]);

                $lawyer->wallet -= $offerPackage->price;
                $lawyer->save();

                $this->walletTransaction->create([
                    'user_id' => $lawyer->id,
                    'user_type' => 'lawyer',
                    'debit' => $offerPackage->price,
                    'credit' => 0,
                    'comment' => 'تم خصم المبلغ من المحفظة بنجاح',
                ]);
            }
            return $this->responseMsg('the ad package has been assigned to the lawyer successfully', LawyerPackageResource::make($lawyerPackage), 200);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getAdOfferPackages()
    {
        try {
            $adOfferPackages = $this->offerPackageService->model->where('status', StatusEnum::ACTIVE->value)->get();
            return AdOfferPackageResource::collection($adOfferPackages);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getLawyerPackageAds($id)
    {
        try {
            $data['package'] = LawyerPackageResource::make($this->lawyerPackageService->model->where('id', $id)->first());
            $data['ads'] = AdResource::collection($this->lawyerPackageService->model->where('id', $id)->first()->ads);
            return $this->responseMsg('the ads for the lawyer package has been returned successfully', $data, 200);
        } catch (\Exception $e) {
            return $this->responseMsg("error returning the data for the ads of the lawyer package", null, 500);
        }
    }

    public  function getLawyerAdPackages()
    {
        try {
            $lawyerPackages = auth('lawyer_api')->user()->lawyerPackages()->with('offerPackage')->get();
            //            $offerPackages = $lawyerPackages->pluck('offerPackage')->unique()->values();
            return $this->responseMsg('the lawyer packages has been returned successfully', LawyerPackageResource::collection($lawyerPackages), 200);
        } catch (\Exception $e) {
            return $this->responseMsg('error happened while returning the lawyer packages', null, 500);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getMarketProduct($id)
    {
        try {
            $marketProduct = $this->marketProductService->model->where('id', $id)->first();
            if ($marketProduct) {
                return $this->successResponse($marketProduct);
            } else {
                return $this->responseMsg('please enter a valid market product id', null, 422);
            }
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public  function getAllMarketProducts()
    {
        try {
            return $this->successResponse(MarketProductResource::collection($this->marketProductService->model->all()));
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function addOrder($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'market_product_id' => 'required|exists:market_products,id',
                'phone' => 'required|string',
                'qty' => 'required|integer',
                'address' => 'required|string'
            ], [
                'market_product_id.required' => 'market product is required',
                'phone.required' => 'phone is required',
                'qty' => 'quantity is required',
                'address' => 'address is required'
            ]);

            if ($validator) {
                return $validator;
            }
            $lawyer = auth('lawyer_api')->user();
            if ($lawyer) {
                $order = $this->orderService->model->create([
                    'market_product_id' => $request->market_product_id,
                    'lawyer_id' => $lawyer->id,
                    'qty' => $request->qty,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'total_price' => $request->qty * $this->marketProductService->model->where('id', $request->market_product_id)->first()->price,
                    'status' => OrderStatusEnum::NEW->value
                ]);

                return $this->responseMsg('the order has been placed successfully', OrderResource::make($order), 201);
            } else {
                return $this->responseMsg('error happened while placing the order', null, 422);
            }
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }
}
