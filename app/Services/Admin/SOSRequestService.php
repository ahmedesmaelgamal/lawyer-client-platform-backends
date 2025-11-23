<?php

namespace App\Services\Admin;

use App\Enums\SosRequestStatusEnum;
use App\Models\SOSRequest as ObjModel;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;

class SOSRequestService extends BaseService
{
    protected string $folder = 'admin/s_o_s_request';
    protected string $route = 's_o_s_requests';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->editColumn('problem', function ($obj) {
                    return $obj->problem;
                })
                ->editColumn('phone', function ($obj) {
                    return $obj->phone;
                })
                ->editColumn('address', function ($obj) {
                    return $obj->address;
                })
                ->editColumn('lat', function ($obj) {
                    return $obj->lat;
                })
                ->editColumn('long', function ($obj) {
                    return $obj->long;
                })
                ->editColumn('status', function ($obj) {
                    $newRequest = '<span class="badge badge-primary">New</span>';
                    $acceptedRequest = '<span class="badge badge-success">Accepted</span>';
                    $completedRequest = '<span class="badge badge-danger">Completed</span>';
                    switch ($obj->status) {
                        case 'new':
                            return $newRequest;
                        case 'accepted':
                            return $acceptedRequest;
                        case 'completed':
                            return $completedRequest;
                        default:
                            return $obj->status;
                    }
                })
                ->editColumn('lawyer_id', function ($obj) {
                    return ($obj->lawyer) ? @$obj->lawyer->name : "";
                })
                ->editColumn('client_id', function ($obj) {
                    return @$obj->client->name;
                })
                //
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
            $data['image'] = $this->handleFile($data['image'], 'SOSRequest');
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
            $data['image'] = $this->handleFile($data['image'], 'SOSRequest');

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
