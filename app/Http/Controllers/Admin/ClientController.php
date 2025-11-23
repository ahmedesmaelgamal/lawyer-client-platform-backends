<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest as ObjRequest;
use App\Models\City;
use App\Models\Client as ObjModel;
use App\Models\Country;
use App\Services\Admin\ClientService as ObjService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }
    public function show($id)
    {
        return $this->objService->show($id);
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

    public function edit(ObjModel $client)
    {
        return $this->objService->edit($client);
    }

    public function update(ObjRequest $request, $id)
    {
        $data = $request->validated();
        return $this->objService->update($data, $id);
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


}
