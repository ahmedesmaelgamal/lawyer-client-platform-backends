<?php

namespace App\Services\Admin;

use App\Enums\StatusEnum;
use App\Models\Level as LevelObj;
use App\Models\Speciality as ObjModel;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SpecialityService extends BaseService
{
    protected string $folder = 'admin/speciality';
    protected string $route = 'specialities';
    protected LevelObj $levelObj;

    public function __construct(ObjModel $objModel , LevelObj $levelObj)
    {
        $this->levelObj=$levelObj;
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->editColumn('title', function ($obj) {
                    return $obj->title;
                })
                ->editColumn('level_id', function ($obj) {
                    return @$obj->Level->title;
                })
                ->editColumn('status', function ($obj) {
                    return $this->StatusDatatableCustom($obj,StatusEnum::ACTIVE->value);
                })
//                ->editColumn('speciality_id', function ($obj) {
//                    return $obj->Level->title;
//                })
                ->addColumn('action', function ($obj) {
                    $buttons = '';

                    if (Auth::user()->can("update_speciality_management")) {
                        $buttons .= '
                            <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                                <i class="fa fa-edit"></i>
                            </button>
                        ';
                    }

                    if (Auth::user()->can("delete_speciality_management")) {
                        $buttons .= '
                            <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                                data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                                <i class="fas fa-trash"></i>
                            </button>
                        ';
                    }

                    return $buttons;

                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => trns($this->route),
                'route' => $this->route,
            ]);
        }
    }

    public function create()
    {        $levels = $this->levelObj->all();
        $statuses = StatusEnum::cases();
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'levels' => $levels,
            'statuses' => $statuses,
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Speciality');
        }

        try {
            $this->createData($data);
            return response()->json(['status' => 200, 'message' => trns('Data created successfully.')]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => trns('Something went wrong.'), trns('error') => $e->getMessage()]);
        }
    }

    public function edit($obj)
    {
        $levels = $this->levelObj->all();
        $statuses = StatusEnum::cases();
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
            'levels' => $levels,
            'statuses' => $statuses,
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Speciality');

            if ($oldObj->image) {
                $this->deleteFile($oldObj->image);
            }
        }

        try {
            $oldObj->update($data);
            return response()->json(['status' => 200, 'message' => trns('Data updated successfully.')]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => trns('Something went wrong.'), trns('error') => $e->getMessage()]);
        }
    }
}
