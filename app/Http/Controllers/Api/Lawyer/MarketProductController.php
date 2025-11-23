<?php

namespace App\Http\Controllers\Api\Lawyer;

use App\Http\Controllers\Controller;
use App\Services\Api\Lawyer\MainService;
use App\Services\Api\Lawyer\MarketProductService;
use Illuminate\Http\Request;

class MarketProductController extends Controller
{
    public function __construct(protected MarketProductService $marketProductService)
    {
    }

    public function getMarketProduct($id)
    {
        return $this->marketProductService->getMarketProduct($id);
    }
    public function getAllMarketProducts()
    {
        return $this->marketProductService->getAllMarketProducts();
    }
    public function addOrder(Request $request)
    {
        return $this->marketProductService->addOrder($request);
    }
    public function getOrders()
    {
        return $this->marketProductService->getOrders();
    }
    public function getMarketProductHome(Request $request)
    {
        return $this->marketProductService->getMarketProductHome($request);
    }

}
