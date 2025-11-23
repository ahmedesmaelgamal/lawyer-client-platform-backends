<?php

namespace App\Services\Api\Lawyer;


use App\Enums\OrderStatusEnum;
use App\Http\Resources\MarketOfferResource;
use App\Http\Resources\MarketProductCategoryResource;
use App\Http\Resources\MarketProductResource;
use App\Http\Resources\OrderResource;
use App\Models\Lawyer as ObjModel;
use App\Models\MarketProductCategory;
use App\Services\Admin\MarketOfferService;
use App\Services\Admin\MarketProductCategoryService;
use App\Services\Admin\OrderService;
use App\Services\BaseService;
use Exception;


class MarketProductService extends BaseService
{
    public function __construct(
        ObjModel                                           $model,
        protected \App\Services\Admin\MarketProductService $marketProductService,
        protected OrderService                             $orderService,
        protected MarketProductCategoryService             $marketProductCategoryService,
        protected MarketOfferService                       $marketOfferService,
    )
    {
        parent::__construct($model);
    }

    public function getAllMarketProducts()
    {
        try {
            return $this->successResponse(MarketProductResource::collection($this->marketProductService->model->all()));
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }
public function getMarketProduct($id)
{
    try {
        return $this->successResponse(MarketProductResource::make($this->marketProductService->model->where('id',$id)->first()));
    }catch (Exception $e){
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

    public function getOrders()
    {
        try {
            $lawyer = auth('lawyer_api')->user();
            $orders= $this->orderService->model->where('lawyer_id',$lawyer->id)->get();
            return $this->responseMsg('the orders has been returned successfully',OrderResource::collection($orders),200);
        } catch (Exception $e) {
            return $this->responseMsg('error returning the orders',null,500);
        }
    }

    public function getMarketProductHome($request)
    {

        try {
            $data['market_offer'] = MarketOfferResource::collection($this->marketOfferService->model->all());
            $data['market_product_category'] = MarketProductCategoryResource::collection($this->marketProductCategoryService->model->all());
            if (!$request->search) {
                if ($request->category == 0) {
                    $marketProduct = $this->marketProductService->model->all();
                } else {
                    $marketProduct = $this->marketProductService->model->where('market_product_category_id', $request->category)->get() ?? $this->responseMsg('invalid category id', null, 500);
                }

                $data['market_product'] = MarketProductResource::collection($marketProduct);

            } else {
                $marketProduct = $this->marketProductService->model->where('title', 'like', '%' . $request->search . '%')->get();
                $data['market_product'] = MarketProductResource::collection($marketProduct);
            }
            return $this->successResponse($data);


        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }
}
