<?php

namespace App\Services\Admin;

use App\Enums\CourtCaseStatusEnum;
use App\Models\Client as ClientObj;
use App\Models\CourtCase as ObjModel;
use App\Models\Speciality as Speciality;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CourtCaseService extends BaseService
{
    protected string $folder = 'admin/court_case';
    protected string $route = 'court_cases';
    protected ClientObj $clientObj;
    protected Speciality $specialityObj;
    public function __construct(ObjModel $objModel, ClientObj $clientObj, Speciality $specialityObj)
    {
        $this->objModel = $objModel;
        $this->clientObj = $clientObj;
        $this->specialityObj = $specialityObj;
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {

            $obj = $this->model->query();

            $obj->when($request->search_client_id, function ($query) use ($request) {
                $query->where('client_id', 'like', '%' . $request->search_client_id . '%');
            })
                // ->when($request->has('search_speciality_id') && $request->search_speciality_id, function ($query) use ($request) {
                //     $query->where('search_speciality_id', 'like', '%' . $request->search_speciality_id . '%');
                // })
                ->when($request->search_speciality_id, function ($query) use ($request) {
                    $query->whereHas('speciality', function ($subQuery) use ($request) {
                        $subQuery->where('id', $request->search_speciality_id);
                    });
                })
                ->when($request->has('search_status') && $request->search_status, function ($query) use ($request) {
                    $query->where('status', $request->search_status);
                });
            $obj->get();

            $dataTable = DataTables::of($obj)
                ->editColumn('title', function ($obj) {
                    return @$obj->title ? $obj->title : "-";
                })
                ->editColumn('client_id', function ($obj) {
                    return @$obj->client->name;
                })
                ->editColumn('case_estimated_price', function ($obj) {
                    return $obj->case_estimated_price;
                })
                // ->editColumn('details', function ($obj) {
                //     return $obj->details;
                // })

                ->editColumn('case_number', function ($obj) {
                    return $obj->case_number;
                })
                ->editColumn('status', function ($obj) {
                    return $obj->status;
                })
                ->editColumn('case_final_price', function ($obj) {
                    return $obj->case_final_price;
                })
                ->editColumn('speciality_id', function ($obj) {
                    return @$obj->speciality?->title ?? '-';
                })
                ->addColumn('action', function ($obj) {
                    $buttons = ' ';

                    $buttons .= '<a href="' . route('court_cases.show', $obj->id) . '" class="btn btn-pill btn-primary-light" ' . $obj->id . '>
                            <i class="fas fa-eye"></i>
                        </a>  ';
                    return $buttons;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);

            // dd($dataTable);
            return $dataTable;
        } else {

            $searchValues = [
                'search_name' => $request->search_name,
                'search_email' => $request->search_email,
                'search_type' => $request->search_type,
                'search_status' => $request->search_status,
                'search_country_id' => $request->search_country_id,
                'search_city_id' => $request->search_city_id,
                'search_level_id' => $request->search_level_id,
            ];


            $specialities = $this->specialityObj->pluck('title', 'id')->toArray();
            $clients = $this->clientObj->pluck('name', 'id')->toArray();
            $statuses = CourtCaseStatusEnum::cases();
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => trns($this->route),
                'route' => $this->route,
                'clients' => $clients,
                'specialities' => $specialities,
                'statuses' => $statuses,
            ]);
        }
    }

    public function create()
    {
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
        ]);
    }

    public function show($id)
    {
        // dd($id);
        $courtCase = $this->objModel->where('id', $id)->with('speciality', 'courtCaseEvents', 'courtCaseUpdates', 'courtCaseDues', 'courtCaseCancellations', 'client', 'courtCaseFiles')->first();
        return view("{$this->folder}/parts/show", [
            'obj' => $courtCase,
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'CourtCases');
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
            $data['image'] = $this->handleFile($data['image'], 'CourtCases');

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
