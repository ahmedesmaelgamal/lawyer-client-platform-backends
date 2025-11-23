<?php

namespace App\Services\Admin;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\Admin;
use App\Models\Admin as ObjModel;
use App\Models\Setting;
use Spatie\Activitylog\Models\Activity as ActivityObj;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Role as RoleObj;
use Yajra\DataTables\DataTables;

class AdminService extends BaseService
{
    protected string $folder = 'admin/admin';
    protected string $route = 'admins';
    protected ActivityObj $activityObj;
    protected RoleObj $roleObj;
    protected ObjModel $objModel;


    public function __construct(
        ObjModel $model, ActivityObj $activityObj, RoleObj $roleObj, ObjModel $objModel
    )
    {
        $this->objModel = $objModel;
        $this->activityObj = $activityObj;
        $this->roleObj = $roleObj;
        parent::__construct($model);
    }

    public function index($request)
    {

        if ($request->ajax()) {
            $admins = $this->getDataTable()->filter(function ($admin) {
                return $admin->id != Auth::id();
            });
            return DataTables::of($admins)
                ->editColumn('role', function ($admins) {
//                    dd($admins->role);
                    return $admins->getRoleNames()->first();

                })
                ->addColumn('action', function ($admins) {
                    $buttons = '';
                    if ($admins->id != 1 || auth()->guard('admin')->user()->id == 1) {
                        if (Auth::user()->can("update_admin_management")) {
                            $buttons .= '
                                <button type="button" data-id="' . $admins->id . '" class="btn btn-pill btn-info-light editBtn">
                                <i class="fa fa-edit"></i>
                                </button>';
                        }
                    }

                    if (auth()->guard('admin')->user()->id != $admins->id && $admins->id != 1) {
                        if (Auth::user()->can("delete_admin_management")) {
                            $buttons .= '
                            <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $admins->id . '" data-title="' . $admins->name . '">
                            <i class="fas fa-trash"></i>
                            </button>
                        ';
                        }
                    }

                    return $buttons;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {

            return view($this->folder . '/index');
        }
    }


    public function myProfile()
    {
        $admin = auth()->guard('admin')->user();
        $activities = $this->activityObj->where('causer_id', Auth::user()->id)->get();
//        dd($this->objModel->first()->name);
//        $adminActivities=$this->objModel->first()->name;
        return view($this->folder . '/profile',
            [
                'admin' => $admin,
                'createRoute' => route($this->route . '.create'),
                'bladeName' => trns($this->route),
                'route' => 'admins',
                'updateRoute' => 'myProfile',
                'activities' => $activities,
//                'adminName'=>$this->objModel->where('id',Auth::user()->id)->first()->name
            ]
        );
    }


    public function create()
    {
        $code = $this->generateCode();
        $roles = $this->roleObj->all();
        return view($this->folder . '/parts/create', [
            'code' => $code,
            'storeRoute' => route($this->route . '.store'),
            'roles' => $roles
        ]);
    }


    public function store($data): JsonResponse
    {
//        dd($data);
        $data['password'] = Hash::make($data['password']);
        $role = $this->roleObj->find($data['role_id'])->first();
//dd($role);
        if (!$role) {
            return response()->json(['status' => 404, 'message' => 'Role not found']);
        }

        if (isset($data['image'])) {
//            dd('ksdnfl');
            $data['image'] = $this->handleFile($data['image'], 'admin');
        }

        unset($data['role_id']);
//        dd($data);
        $model = $this->createData($data);
        $admin = $this->model->where('code', $data['code'])->first();
        $admin->assignRole([$role->name]);

        if ($model) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function edit($admin)
    {
//        dd($admin->id);
        $roles = $this->roleObj->all();
        return view($this->folder . '/parts/edit', [
            'admin' => $admin,
            'updateRoute' => route($this->route . '.update', $admin->id),
            'roles' => $roles

        ]);
    }

    public function editProfile()
    {
//        dd($admin);
        return view($this->folder . '/parts/edit_profile', [
//            'admin' => $admin,
            'updateRoute' => route('myProfile' . '.update'),
        ]);
    }

    public function editProfileImage()
    {

        return view($this->folder . '/parts/edit_profile_image', [
//            'admin' => $admin,
            'updateRoute' => route('myProfile' . '.update.image'),
        ]);
    }

    public function update($data, $id)
    {

        if ($data['password'] && $data['password'] != null) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'admin');
        }
        $role = $this->roleObj->find($data['role_id']);
        if (!$role) {
            return response()->json(['status' => 404, 'message' => 'Role not found']);
        }
        unset($data['role_id']);
        $admin = $this->getById($id);
        $model = $this->updateData($id, $data);
        $admin->syncRoles([$role->name]);
        if ($model) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function updateProfile($data)
    {
//        dd(Auth::user()->id);
//        if (isset($data['image'])) {
//            $oldObj = $this->getById(Auth::user()->id);
//            $data['image'] = $this->handleFile($data['image'], 'admin');
//
//            if ($oldObj->image) {
//                $this->deleteFile($oldObj->image);
//            }
//        }


        if ($data['password'] && $data['password'] != null) {

            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }


        if ($this->updateData(Auth::user()->id, $data)) {
            return response()->json(['status' => 200,'redirect'=>route('myProfile')]);
        } else {
            return response()->json(['status' => 405]);
        }


    }


    public function updateProfileImage($data)
    {
        $oldObj = $this->getById(Auth::user()->id);
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'admin');
        }
        if ($oldObj->image) {
            $this->deleteFile($oldObj->image);
        }

        if ($this->updateData(Auth::user()->id, $data)) {
            return response()->json(['status' => 200,'redirect'=>route('myProfile')]);
        } else {
            return response()->json(['status' => 405]);
        }


    }

    protected function generateCode(): string
    {
        do {
            $code = Str::random(11);
        } while ($this->firstWhere(['code' => $code]));

        return $code;
    }

}
