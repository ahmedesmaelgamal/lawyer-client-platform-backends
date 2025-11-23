<?php

namespace App\Services\Admin;

use App\Models\Rate as ObjModel;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class RateService extends BaseService
{
    protected string $folder = 'admin/rate';
    protected string $route = 'rates';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->editColumn('user_id', function ($obj) {
                    return $obj->user_id;
                })
                ->editColumn('case_id', function ($obj) {
                    return $obj->case_id;
                })
                ->editColumn('user_type', function ($obj) {
                    return $obj->user_type;
                })
                ->editColumn('rate', function ($obj) {
                    return $obj->rate;
                })
                ->editColumn('comment', function ($obj) {
                    return $obj->comment;
                })

                ->addColumn('action', function ($obj) {
                    $buttons = '';

                    if (Auth::user()->can("update_rates_management")) {
                        $buttons .= '
                            <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                                <i class="fa fa-edit"></i>
                            </button>
                        ';
                    }

                    if (Auth::user()->can("delete_rates_management")) {
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
    {
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Rate');
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
            $data['image'] = $this->handleFile($data['image'], 'Rate');

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
