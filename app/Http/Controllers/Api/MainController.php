<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\MainService;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function __construct(protected MainService $mainService)
    {
    }


    public function specialities()
    {
        return $this->mainService->specialities();
    }

    public function levels()
    {
        return $this->mainService->levels();
    }

    public function specialitiesById($id)
    {
        return $this->mainService->specialitiesById($id);
    }

    public function getLawyers()
    {
        return $this->mainService->getLawyers();
    }

    public function country()
    {
        return $this->mainService->country();
    }

    public function city($id)
    {
        return $this->mainService->city($id);
    }

    public function getCancelReasons()
    {
        return $this->mainService->getCancelReasons();
    }

    public function getFinishReasons()
    {
        return $this->mainService->getFinishReasons();
    }
    public function getSettings()
    {
        return $this->mainService->getSettings();
    }

    public function testFcm()
    {
        return $this->mainService->testFcm();
    }
    public function testFcmResponse(Request $request)
    {
        return $this->mainService->testFcmResponse($request);
    }

    public function getCourtCaseLevel()
    {
        return $this->mainService->getCourtCaseLevel();
    }

    public function getCaseSpecializations()
    {
        return $this->mainService->getCaseSpecializations();
    }
    public function getSubCaseSpecializations($id){
        return $this->mainService->getSubCaseSpecializations($id);
    }
}
