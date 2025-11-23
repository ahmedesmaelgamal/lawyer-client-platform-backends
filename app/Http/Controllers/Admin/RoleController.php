<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest as ObjRequest;
use App\Services\Admin\RoleService as ObjService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role as ObjModel;

class RoleController extends Controller
{
    public function __construct(protected ObjService $objService){}

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }

    public function destroy($id)
    {
        return $this->objService->delete($id);
    }

    public function create()
    {
        return $this->objService->create();
    }

    public function store(ObjRequest $request)
    {
        $data = $request->validated();
        return $this->objService->store($data);
    }

    public function edit(ObjModel $role)
    {
        return $this->objService->edit($role);
    }

    public function update(ObjRequest $request, $id)
    {
        $data = $request->validated();
        return $this->objService->update($id, $data);
    }
}
