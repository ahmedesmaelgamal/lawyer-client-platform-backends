<?php

namespace App\Http\Controllers\Admin;


use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CaseSpecializationRequest as ObjRequest;
use App\Models\CaseSpecialization as ObjModel;
use App\Models\SubCaseSpecializations;
use App\Services\Admin\CaseSpecializationService as ObjService;
use Illuminate\Http\Request;

class CaseSpecializationController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }

    public function create()
    {
        return $this->objService->create();
    }

    public function store(ObjRequest $data)
    {
        // dd($data);
        $data = $data->validated();
        return $this->objService->store($data);
    }

    public function edit(ObjModel $case_specialization)
    {
        return $this->objService->edit($case_specialization);
    }

    public function update(ObjRequest $request, $id)
    {
        $data = $request->validated();
        return $this->objService->update($data, $id);
    }

    public function destroy($id)
    {
        $caseSpecialization = ObjModel::where('id', $id)->first();
        $SubCaseSpecialization = SubCaseSpecializations::where('case_specializations_id', $id)->get();
        if ($caseSpecialization) {
            $SubCaseSpecialization->each->delete();
            $caseSpecialization->delete();             // امسح الأب
            // dd('welcome');
        }
        return response()->json(['status' => 200, 'message' => trns('deleted_successfully')]);
    }


    public function deleteSelected(Request $request)
    {
        return $this->objService->deleteSelected($request);
    }

    public function changeStatus(Request $request)
    {
        return $this->objService->changeStatus($request);
    }
    public function updateColumnSelected(Request $request)
    {
        return $this->objService->updateColumnSelected($request, 'status', "status", StatusEnum::values());
    }
}
