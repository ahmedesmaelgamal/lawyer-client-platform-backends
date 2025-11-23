<?php

namespace App\Http\Controllers\Api\Lawyer;

use App\Http\Controllers\Controller;
use App\Services\Api\Lawyer\MainService;
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

    public function getOfficeTeam()
    {
        return $this->mainService->getOfficeTeam();
    }



    public function sendOfficeRequest(Request $request)
    {
        return $this->mainService->sendOfficeRequest($request);
    }
    public function getOfficeRequest(){
        return $this->mainService->getOfficeRequest();
    }

    public function sendOfficeResponse(Request $request)
    {
        return $this->mainService->sendOfficeResponse($request);
    }

    public function deleteLawyerFromOffice(Request $request)
    {
        return $this->mainService->deleteLawyerFromOffice($request);

    }
    public function deleteLawyerFromOfficeResponse(Request $request)
    {
        return $this->mainService->deleteLawyerFromOfficeResponse($request);
    }
    public function deleteLawyerFromOfficeRequest()
    {
        return $this->mainService->deleteLawyerFromOfficeRequest();
    }

    public function searchLawyer(Request $request)
    {
        return $this->mainService->searchLawyer($request);
    }
    public function deleteLawyerById(Request $request)
    {
        return $this->mainService->deleteLawyerById($request);
    }

    public function getWalletTransactions()
    {
        return $this->mainService->getWalletTransactions();
    }

    public function withdrawRequest(Request $request)
    {
        return $this->mainService->withdrawRequest($request);
    }


    public function getNotifications()
    {
        return $this->mainService->getNotifications();
    }
    public function changeType()
    {
        return $this->mainService->changeType();
    }
}
