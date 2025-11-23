<?php

namespace App\Http\Controllers\Api\Lawyer;

use App\Services\BaseService;
use App\Models\Lawyer as ObjModel;

use App\Services\Api\Lawyer\CourtCaseService;
use Illuminate\Http\Request;

class CourtCaseController extends BaseService
{
    /**
     * Constructor method
     *
     * @param ObjModel $model
     *
     * @return void
     */
    public function __construct(
        ObjModel $model,
        protected CourtCaseService $courtCaseService,

    ) {
        parent::__construct($model);
    }

    public function courtCase($id)
    {
        return $this->courtCaseService->findcourtCase($id);
    }

    public function myCourtCases(Request $request)
    {
        return $this->courtCaseService->myCourtCases($request);
    }

    public function addEventCourtCase(Request $request)
    {
        return $this->courtCaseService->addEventCourtCase($request);
    } public function deleteEventCourtCase($id)
    {
        return $this->courtCaseService->deleteEventCourtCase($id);
    }

    public function actionCourtCase(Request $request)
    {
        return $this->courtCaseService->actionCourtCase($request);
    }

    public function addCourtCaseDues(Request $request)
    {
        return $this->courtCaseService->addCourtCaseDues($request);
    }

    public function addNewUpdate(Request $request)
    {
        return $this->courtCaseService->addNewUpdate($request);
    }
    public function updateCourtCaseUpdate(Request $request ,$id)
    {
        return $this->courtCaseService->updateCourtCaseUpdate($request , $id);
    }

    public function deleteCourtCaseUpdate($id)
    {
        return $this->courtCaseService->deleteCourtCaseUpdate($id);
    }
    public function deleteCourtCaseUpdateFile($id)
    {
        return $this->courtCaseService->deleteCourtCaseUpdateFile($id);
    }
    public function transferCourtCaseToAnotherLawyerRequest(Request $request)
    {
//        dd($request);
        return $this->courtCaseService->transferCourtCaseToAnotherLawyerRequest($request);
    }
    public function transferCourtCaseToAnotherLawyerResponse(Request $request)
    {
        return $this->courtCaseService->transferCourtCaseToAnotherLawyerResponse($request);
    }
    public function getAllTransferCourtCases()
    {
        return $this->courtCaseService->getAllTransferCourtCases();
    }
    public function getAllContributionCourtCases()
    {
        return $this->courtCaseService->getAllContributionCourtCases();
    }

    public function courtCaseTransferRequest(Request $request)
    {
        return $this->courtCaseService->courtCaseTransferRequest($request);

    }
    public function courtCaseContributionRequest(Request $request)
    {
        return $this->courtCaseService->courtCaseContributionRequest($request);

    }

    public function addLawyerToCourtCaseRequest(Request $request)
    {
        return $this->courtCaseService->addLawyerToCourtCaseRequest($request);
    }
    public function addLawyerToCourtCaseResponse(Request $request)
    {
        return $this->courtCaseService->addLawyerToCourtCaseResponse($request);
    }


//    public function deleteAccount(Request $request)
//    {
//        return $this->courtCaseService->deleteAccount($request);
//    }


}
