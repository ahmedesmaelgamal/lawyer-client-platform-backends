<?php

namespace App\Services\Admin;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission as PermissionObj;
use Spatie\Permission\Models\Role as RoleObj;
use Yajra\DataTables\DataTables;

class RoleService extends BaseService
{
    protected string $folder = 'admin/role';
    protected string $route = 'roles';
    protected RoleObj $roleObj;
    protected PermissionObj $permissionObj;

    public function __construct(PermissionObj $permissionObj,RoleObj $roleObj)
    {
        $this->roleObj=$roleObj;
        $this->permissionObj=$permissionObj;
        parent::__construct($roleObj);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $models = $this->model->all();
            return DataTables::of($models)
                ->addColumn('action', function ($models) {
                $buttons = '';

                if (Auth::user()->can("update_roles_management")) {
                    $buttons .= '
                        <button type="button" data-id="' . $models->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                    ';
                }

                if (Auth::user()->can("delete_roles_management")) {
                    $buttons .= '
                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $models->id . '" data-title="' . $models->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                }

                if ($models->id > 8) {
                    if (Auth::user()->can("delete_roles_management")) {
                        $buttons .= '
                            <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                                data-bs-target="#delete_modal" data-id="' . $models->id . '" data-title="' . $models->name . '">
                                <i class="fas fa-trash"></i>
                            </button>
                        ';
                    }
                }

                return $buttons;
            })

                ->addColumn('name',function($model){
                    return trns($model->name);
                })
                ->addColumn('permissions', function ($models) {
                    return $models->permissions->count() > 0 ? '<span class="badge badge-success">' .
                       $models->permissions->count() .' '. trns('permissions')
                        . '</span>' :
                        'No Permissions';
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'route' => $this->route
            ]);
        }
    }

    public function create()
    {
        $permissions = $this->permissionObj->all();
        $roles = $this->roleObj->all();
        return view($this->folder . '/parts/create', [
            'permissions' => $permissions,
            'storeRoute' => route($this->route . '.store'),
            'roles'=>$roles
        ]);
    }


    public function store($data): JsonResponse
    {
        $model = $this->createData($data);

        if ($model) {
            $permissions = $this->permissionObj->query()
                ->whereIn('name', $data['permissions'])
                ->where('guard_name', $data['guard_name'])
                ->get();
            $model->syncPermissions($permissions);

            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function edit($role)
    {
        return view($this->folder . '/parts/edit', [
            'obj' => $role,
            'old_permissions' => $role->permissions()->pluck('name')->toArray(),
            'updateRoute' => route($this->route . '.update', $role->id),
        ]);
    }

    public function update($id, $data)
    {
        $model = $this->getById($id);

        if ($this->updateData($id, $data)) {
            $permissions = $this->permissionObj->query()->whereIn('name', $data['permissions'])->get();
            $model->syncPermissions($permissions);
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }
}
