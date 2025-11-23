<?php

namespace App\Services\Admin;

use App\Services\BaseService;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity as ObjModel;
use Yajra\DataTables\DataTables;
use App\Models\Admin as AdminObj;

class ActivityLogService extends BaseService
{
    protected string $folder = 'admin/activity_log';
    protected string $route = 'activity_logs';
    protected AdminObj $adminObj;

    public function __construct(protected ObjModel $objModel,AdminObj $adminObj)
    {
        $this->adminObj=$adminObj;
        parent::__construct($objModel);
    }


    public function index($request)
    {
        if ($request->ajax()) {

            $obj = $this->getDataTable();
//            dd($obj->first());
//            dd($this->adminObj->first()->name);
//            dd($this->adminObj->name);
//            dd($this->adminObj->where('name',$obj->causer_id)->first());
            return DataTables::of($obj)
                ->editColumn('description', function ($obj) {
                    return $obj->description;
                })
                ->editColumn('subject_type', function ($obj) {
                    return class_basename($obj->subject_type);
//                    return $obj->subject_type;
                })
                ->editColumn('subject_id', function ($obj) {
                    return $obj->subject_id;
                })
//                ->editColumn('causer_type', function ($obj) {
//                    return class_basename($obj->causer_type);
////                    return Str::match('*',$obj->causer_type);
////                    return $obj->causer_type;
//                })
                ->editColumn('causer_id', function ($obj) {
//                    return $this->adminObj->first()->name ;
//                    return class_basename($obj->subject_type);
                    return $this->adminObj->where('id', $obj->causer_id)->first()->name??"";
                })
                ->addColumn('action', function ($obj) {
                    $buttons = '
                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    return $buttons;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                // 'createRoute' => route($this->route . '.create'),
                'bladeName' => trns($this->route),
                'route' => $this->route,
            ]);
        }
    }

    public function deleteall(){
        try {
                    $this->model->truncate(); 
                    return response()->json(['status' => 200, 'message' => trns('deleted_successfully')]);
            } catch (\Exception $e) {
                return response()->json(['status' => 500, 'message' => trns('something_went_wrong')]);
            }
    }
}
