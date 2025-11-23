<?php

namespace App\Http\Controllers\Api\Lawyer;

use App\Http\Controllers\Controller;
use App\Services\Api\Lawyer\AdService;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function __construct(protected AdService $adService)
    {
    }

    public function addAdOfferPackage(Request $request)
    {
        return $this->adService->addAdOfferPackage($request);
    }
    public function addAdToPackageLawyer(Request $request)
    {
        return $this->adService->addAdToPackageLawyer($request);
    }
    public function getAdOfferPackages()
    {
        return $this->adService->getAdOfferPackages();
    }
    public function getLawyerAdPackages()
    {
        return $this->adService->getLawyerAdPackages();
    }
    public function getLawyerPackageAds($id)
    {
        return $this->adService->getLawyerPackageAds($id);
    }
    public function addAdToLawyerPackage(Request $request)
    {
        return $this->adService->addAdToLawyerPackage($request);
    }
    public function addAdOfferPackageToLawyer(Request $request)
    {
        return $this->adService->addAdOfferPackageToLawyer($request);
    }

}
