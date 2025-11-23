<?php

namespace App\Services\Admin;

use App\Enums\StatusEnum;
use App\Models\CourtCaseCancellation as ObjModel;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CourtCaseCancellationService extends BaseService
{
    protected string $folder = 'admin/court_case_cancellation';
    protected string $route = 'court_case_cancellation';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->editColumn('court_case_id', function ($obj) {
                    return $obj->courtCase->title;
                })
                ->editColumn('accept_lawyer', function ($obj) {
                    $acceptLawyer = '<span class="badge badge-success">Yes</span>';
                    $rejectLawyer = '<span class="badge badge-danger">No</span>';

                    return $obj->accept_lawyer == 1 ? $acceptLawyer : $rejectLawyer;
                })
                ->editColumn('accept_client', function ($obj) {
                    $acceptClient = '<span class="badge badge-success">Yes</span>';
                    $rejectClient = '<span class="badge badge-danger">No</span>';

                    return $obj->accept_client == 1 ? $acceptClient : $rejectClient;
                })
                ->editColumn('status', function ($obj) {
                    return $this->StatusDatatableCustom($obj,StatusEnum::ACTIVE->value);
                })

                ->addColumn('action', function ($obj) {
                        $buttons = ' ';
                    if (Auth::user()->can("update_court_case_management")) {

                        $buttons .= '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                    ';
                    }
                    if (Auth::user()->can("delete_court_case_management")) {

                        $buttons .= '<button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>';
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
            $data['image'] = $this->handleFile($data['image'], 'CourtCaseCancellation');
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
            $data['image'] = $this->handleFile($data['image'], 'CourtCaseCancellation');

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
