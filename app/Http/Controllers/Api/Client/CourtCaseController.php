<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Services\Api\Client\CourtCaseService;
use Illuminate\Http\Request;

class CourtCaseController extends Controller
{
    public function __construct(protected CourtCaseService $mainService) {}

    public function addPrivateCase(Request $request)
    {
        return $this->mainService->addPrivateCase($request);
    }

    public function addNewCourtCase(Request $request)
    {
        return $this->mainService->addNewCourtCase($request);
    }

    public function getCourtCases($type)
    {
        return $this->mainService->getCourtCases($type);
    }

    public function cancelCourtCase(Request $request)
    {
        return $this->mainService->cancelCourtCase($request);
    }

    public function finishCourtCase(Request $request)
    {
        return $this->mainService->finishCourtCase($request);
    }

    public function getCourtCase($id)
    {
        return $this->mainService->getCourtCase($id);
    }

    public function actionEvent(Request $request)
    {
        return $this->mainService->actionEvent($request);
    }

    public function getCourtCaseDeus($id)
    {
        return $this->mainService->getCourtCaseDeus($id);
    }

    public function payDue($id)
    {
        return $this->mainService->payDue($id);
    }

    public function transferCourtCaseToAnotherLawyerResponse(Request $request)
    {
        return $this->mainService->transferCourtCaseToAnotherLawyerResponse($request);
    }
    public function courtCaseTransferRequest(Request $request)
    {
        return $this->mainService->courtCaseTransferRequest($request);
    }
    public function getAllTransferCourtCases()
    {
        return $this->mainService->getAllTransferCourtCases();
    }

}
