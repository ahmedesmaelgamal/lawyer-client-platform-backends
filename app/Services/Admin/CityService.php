<?php

namespace App\Services\Admin;

use App\Models\City as ObjModel;
use App\Models\Country as CountryObj;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CityService extends BaseService
{
    protected string $folder = 'admin/city';
    protected string $route = 'cities';
    Protected CountryObj $countryObj;

    public function __construct(ObjModel $objModel , CountryObj $countryObj)
    {
        parent::__construct($objModel);
        $this->countryObj=$countryObj;

    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
//        dd($obj->all());
            return DataTables::of($obj)
                ->editColumn('title', function ($obj) {
                    return $obj->title;  // Fallback for empty values
                })
                ->editColumn('country_id', function ($obj) {
                    return $obj->country ? $obj->country->title : '---';  // Fallback for empty values
                })
                ->addColumn('action', function ($obj) {
                        $buttons = ' ';
                    if (Auth::user()->can("update_city_management")) {

                        $buttons .= '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                    ';
                    }
                        if (Auth::user()->can("delete_city_management")) {
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
        $countries = $this->countryObj->all();
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'countries' => $countries,
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'City');
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
        $countries = $this->countryObj->all();

        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
            'countries' => $countries,
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'City');

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
