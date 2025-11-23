<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContractCategoryRequest as ObjRequest;
use App\Http\Controllers\Controller;
use App\Models\ContractCategory as ObjModel;
use App\Services\Admin\ContractCategoryService as ObjService;
use Illuminate\Http\Request;

class ContractCategoryController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }
//    public function show($id)
//    {
//        return $this->objService->show($id);
//    }

    public function create()
    {
        return $this->objService->create();
    }

    public function store(ObjRequest $data)
    {
        $data = $data->validated();
        return $this->objService->store($data);
    }

    public function edit(ObjModel $contractCategory)
    {
        return $this->objService->edit($contractCategory);
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
        public function updateColumnSelected(\Request $request)
    {
        return $this->objService->updateColumnSelected($request,'status');
    }

    public function deleteSelected(\Request $request){
        return $this->objService->deleteSelected($request);
    }
}
