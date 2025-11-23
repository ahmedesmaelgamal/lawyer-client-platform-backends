<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContractFileRequest as ObjRequest;
use App\Models\ContractFile as ObjModel;
use App\Services\Admin\ContractFileService as ObjService;
use Illuminate\Http\Request;

class ContractFileController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }
    public function showContracts(Request $request,$id)
    {
        return $this->objService->showContracts($request,$id);
    }

    public function show()
    {
        //
    }

    public function create()
    {
        return $this->objService->create();
    }

    public function store(ObjRequest $data)
    {
//        $data['file_path']=file($data->file_path);
        $data = $data->validated();
        return $this->objService->store($data);
    }

    public function edit(ObjModel $contractFile)
    {
        return $this->objService->edit($contractFile);
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
