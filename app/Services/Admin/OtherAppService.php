<?php

namespace App\Services\Admin;

use App\Models\OtherApp as ObjModel;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;

class OtherAppService extends BaseService
{
    use \App\Traits\PhotoTrait;
    protected string $folder = 'admin/other_app';
    protected string $route = 'OtherApps';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    $buttons = '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    return $buttons;
                })->editColumn('icon', function ($obj) {
                    return $this->imageDataTable($obj->icon);
                })->editColumn('status', function ($obj) {
                    return $this->statusDatatable($obj);
                })->editColumn('android_url', fn($obj) => "<a href=\"{$obj->android_url}\" target=\"_blank\" class=\"btn btn-success btn-sm\">Android</a>")
                ->editColumn('ios_url', fn($obj) => "<a href=\"{$obj->ios_url}\" target=\"_blank\" class=\"btn btn-primary btn-sm\">iOS</a>")
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => "",
                'route' => $this->route,
            ]);
        }
    }

    public function create()
    {
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['icon'])) {
            $data['icon'] = $this->handleFile($data['icon'], 'OtherApp');
        }

        try {
            $this->createData($data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => $e->getMessage()]);
        }
    }

    public function edit($obj)
    {
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['icon'])) {
            $data['icon'] = $this->handleFile($data['icon'], 'OtherApp');

            if ($oldObj->icon) {
                $this->deleteFile($oldObj->icon);
            }
        }

        try {
            $oldObj->update($data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => $e->getMessage()]);
        }
    }

    public function changeStatusPackage($request)
    {
        $id = $request->id;
        $newStatus = $request->status;

        if (!in_array($newStatus, [1, 2])) {
            return response()->json(['status' => 400, 'message' => "حالة غير صالحة."]);
        }

        $obj = $this->getById($id);

        if (!$obj) {
            return response()->json(['status' => 404, 'message' => "العرض غير موجود"]);
        }

        if ($obj->status != 0) {
            return response()->json([
                'status' => 403,
                'message' => "لا يمكن تعديل حالة العرض لأنه ليس في وضع الانتظار."
            ]);
        }

        $oldStatus = $obj->status;
        $obj->status = $newStatus;
        $obj->save();

        $statusNames = [
            0 => 'pending',
            1 => 'accepted',
            2 => 'rejected',
            3 => 'expired',
            4 => 'cancelled',
        ];

        $oldStatusName = $statusNames[$oldStatus] ?? 'unknown';
        $newStatusName = $statusNames[$newStatus] ?? 'unknown';

        return response()->json([
            'status' => 200,
            'message' => "تم تغيير الحالة من {$oldStatusName} إلى {$newStatusName} بنجاح"
        ]);
    }
}
