<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest as ObjRequest;
use App\Models\Setting as ObjModel;
use App\Services\Admin\SettingService as ObjService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index(Request $request)
    {

        return $this->objService->index($request);
    }


    // public function edit(ObjModel $model)
    // {
    //     return $this->objService->edit($model);
    // }

    public function update(Request $request)
    {
        // $data = $request->validated();


        return $this->objService->update($request->all());
    }

        public function destroy($id)
    {
        return $this->objService->delete($id);
    }public function updateColumnSelected(Request $request)
    {
        return $this->objService->updateColumnSelected($request,'status',StatusEnum::values());
    }



    public function deleteSelected(Request $request){
        return $this->objService->deleteSelected($request);
    }


    public function secure_settings()
    {
        return $this->objService->secure_settings();
    }

    public function secure_update(Request $request)
    {
        return $this->objService->secure_update($request);
    }

    public function secure_disable(Request $request)
    {
        return $this->objService->secure_disable($request);
    }
    
}
