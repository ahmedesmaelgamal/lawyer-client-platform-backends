<?php

namespace App\Services\Admin;

use App\Enums\UserTypeEnum;
use App\Models\CourtCaseDue as ObjModel;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;

class CourtCaseDueService extends BaseService
{
    protected string $folder = 'admin/court_case_due';
    protected string $route = 'court_case_dues';

    public function __construct(ObjModel $objModel)
    {
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
                ->editColumn('from_user_id', function ($obj) {
                    return $obj->from_user_type == UserTypeEnum::LAWYER->value ? $obj->Fromlawyer->name : $obj->client->name;
                })
                ->editColumn('to_user_id', function ($obj) {
                    return $obj->Tolawyer ? $obj->Tolawyer->name : $obj->Fromlawyer->name;
                })
                ->editColumn('from_user_type', function ($obj) {
                    return $obj->from_user_type;
                })
                ->editColumn('to_user_type', function ($obj) {
                    return $obj->to_user_type;
                })
                ->editColumn('court_case_id', function ($obj) {
                    return $obj->courtCase->title;
                })
                ->editColumn('court_case_event_id', function ($obj) {
                    return $obj->courtCaseEvent->status;
                })
                ->editColumn('date', function ($obj) {
                    return $obj->date;
                })
                ->editColumn('paid', function ($obj) {
                    return $obj->paid==0?'no':'yes';
                })
                // ->addColumn('action', function ($obj) {
                //     $buttons = '

                //         <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                //             data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                //             <i class="fas fa-trash"></i>
                //         </button>
                //     ';
                //     return $buttons;
                // })
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
    {
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'CourtCaseDue');
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
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'CourtCaseDue');

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
