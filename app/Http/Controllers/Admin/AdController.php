<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdConfirmationEnum;
use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdRequest as ObjRequest;
use App\Models\Ad as ObjModel;
use App\Models\Lawyer;
use App\Services\Admin\AdService as ObjService;
use Illuminate\Http\Request;

class AdController extends Controller
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

    public function edit(ObjModel $ad)
    {
        return $this->objService->edit($ad);
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
        return $this->objService->updateColumnSelected($request,'status',StatusEnum::values());
    }

    public function deleteSelected(Request $request){
        return $this->objService->deleteSelected($request);
    }

    public function updateColumnSelectedForConfirmation(Request $request)
    {
        return $this->objService->updateColumnSelectedForConfirmation($request);
    }
}
