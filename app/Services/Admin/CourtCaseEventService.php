<?php

namespace App\Services\Admin;

use App\Models\CourtCaseEvent as ObjModel;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;

class CourtCaseEventService extends BaseService
{
    protected string $folder = 'admin/court_case_event';
    protected string $route = 'court_case_events';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->editColumn('lawyer_id',function ($obj){
                    return $obj->Lawyer->name;
                })
                ->editColumn('status',function ($obj){
                    return $obj->status;
                })
                ->editColumn('price',function ($obj){
                    return $obj->price;
                })
                ->editColumn('court_case_id',function ($obj){
                    return $obj->courtCase->title;
                })
                ->editColumn('seen',function ($obj){
                    return $obj->seen;
                })
                ->editColumn('rate',function ($obj){
                    return $obj->rate;
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
            $data['image'] = $this->handleFile($data['image'], 'CourtCaseEvent');
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
            $data['image'] = $this->handleFile($data['image'], 'CourtCaseEvent');

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
