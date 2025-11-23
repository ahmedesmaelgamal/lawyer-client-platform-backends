<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubCaseSpecializationsRequest as ObjRequest;
use App\Models\SubCaseSpecializations as ObjModel;
use App\Services\Admin\SubCaseSpecializationsService as ObjService;
use Illuminate\Http\Request;

class SubCaseSpecializationsController extends Controller
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
        $data = $data->validated();
        return $this->objService->store($data);
    }

    public function edit($id)
    {
        $obj = $this->objService->getById($id);
        return $this->objService->edit($obj);
    }

    public function update(ObjRequest $request, $id)
    {
        $data = $request->validated();
        return $this->objService->update($data, $id);
    }

    public function destroy($id)
    {

        return $this->objService->delete($id);
    }
    public function updateColumnSelected(Request $request)
    {
        return $this->objService->updateColumnSelected($request, 'status');
    }

    public function deleteSelected(Request $request)
    {
        return $this->objService->deleteSelected($request);
    }

    public function changeStatus(Request $request)
    {

        return $this->objService->changeStatus($request);
    }
}
