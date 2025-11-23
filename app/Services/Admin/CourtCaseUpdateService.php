<?php

namespace App\Services\Admin;

use App\Models\CourtCaseUpdate as ObjModel;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;

class CourtCaseUpdateService extends BaseService
{
    protected string $folder = 'admin/court_case_update';
    protected string $route = 'court_case_updates';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->editColumn('title',function ($obj){
                    return $obj->title;
                })
                ->editColumn('court_case_id',function ($obj){
                    return $obj->courtCase->title;
                })
                ->editColumn('details',function ($obj){
                    return $obj->details;
                })
                ->editColumn('date',function ($obj){
                    return $obj->date;
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
            $data['image'] = $this->handleFile($data['image'], 'CourtCaseUpdate');
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
            $data['image'] = $this->handleFile($data['image'], 'CourtCaseUpdate');

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
