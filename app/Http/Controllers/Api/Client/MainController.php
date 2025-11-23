<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Services\Api\Client\MainService;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function __construct(protected MainService $mainService)
    {

    }

    public function home(Request $request)
    {
        return $this->mainService->home($request);
    }

    public function getLawyers(Request $request)
    {
        return $this->mainService->getLawyers($request);
    }

    public function addSosRequest(Request $request)
    {
        return $this->mainService->addSosRequest($request);
    }

    public function sosLawyers(Request $request)
    {
        return $this->mainService->sosLawyers($request);
    }

    public function getCommunityServiceCategories()
    {
        return $this->mainService->getCommunityServiceCategories();
    }

    public function getCommunityServiceSubCategories($id)
    {
        return $this->mainService->getCommunityServiceSubCategories($id);
    }

    public function getCommunityService($id)
    {
        return $this->mainService->getCommunityService($id);
    }

    public function getLawyerOffers()
    {
        return $this->mainService->getLawyerOffers();
    }

    public function addClientPoints(Request $request)
    {
        return $this->mainService->addClientPoints($request);
    }
    public function pointTransaction(Request $request)
    {
        return $this->mainService->pointTransaction($request);
    }
    public function getCommercialCode()
    {
        return $this->mainService->getCommercialCode();
    }
    public function getClientPoints()
    {
        return $this->mainService->getClientPoint();
    }
    public function getContractFiles(Request $request)
    {
        return $this->mainService->getContractFiles($request);

    }

    public function getContracts(Request $request)
    {
        return $this->mainService->getContracts($request);
    }
    
    public function contractFileCheckout(Request $request)
    {
        return $this->mainService->contractFileCheckout($request);
    }

    public function getContractCategories()
    {
        return $this->mainService->getContractCategories();

    }

    public function getWalletTransactions()
    {
        return $this->mainService->getWalletTransactions();
    }

    public function getNotifications()
    {
        return $this->mainService->getNotifications();
    }


    public function withdrawRequest(Request $request)
    {
        return $this->mainService->withdrawRequest($request);
    }
}
