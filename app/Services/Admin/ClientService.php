<?php

namespace App\Services\Admin;

use App\Enums\StatusEnum as StatusEnumObj;
use App\Models\City as CityObj;
use App\Models\Client as ObjModel;
use App\Models\Country as CountryObj;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ClientService extends BaseService
{
    protected string $folder = 'admin/client';
    protected string $route = 'clients';
    protected CountryObj  $countryObj;
    protected CityObj $cityObj;
    //    protected StatusEnumObj $statusEnumObj;
    protected ObjModel $objModel;
    public function __construct(ObjModel $objModel, CityObj $cityObj, CountryObj $countryObj)
    {
        $this->objModel = $objModel;
        $this->countryObj = $countryObj;
        $this->cityObj = $cityObj;
        //        $this->statusEnumObj=$statusEnumObj;
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->model->query();

            $obj->when($request->search_name, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search_name . '%');
            })
                ->when($request->has('search_email') && $request->search_email, function ($query) use ($request) {
                    $query->where('email', 'like', '%' . $request->search_email . '%');
                })
                ->when($request->has('search_points') && $request->search_points, function ($query) use ($request) {
                    $query->where('points', 'like', '%' . $request->search_points . '%');
                })
                ->when($request->has('search_city_id') && $request->search_city_id, function ($query) use ($request) {
                    $query->where('city_id', 'like', '%' . $request->search_city_id . '%');
                })
                ->when($request->has('search_country_id') && $request->search_country_id, function ($query) use ($request) {
                    $query->where('country_id', 'like', '%' . $request->search_country_id . '%');
                })
                ->when($request->has('search_status') && $request->search_status, function ($query) use ($request) {
                    $query->where('status', $request->search_status);
                });
            $obj->get();

            // Return DataTables response
            return DataTables::of($obj)
            
                ->editColumn('id', function ($obj) {
                    return $obj->id;
                })
                ->editColumn('name', function ($obj) {
                    return $obj->name;
                })
                //                 ->editColumn('image', function ($obj) {
                //                     // Assuming $this->imageDataTable($obj->image) returns the full image URL
                //                     $imageUrl = getFile($obj->image);
                //                     // dd($imageUrl );
                //                     return '<img src="' . $imageUrl . '"  class="image-popup avatar avatar-md rounded-circle" style="cursor:pointer;" width="100" height="100"  alt="Image">';
                //                 })
                //                ->editColumn('image', function ($obj) {
                //                    $imageUrl = getFile($obj->image);
                //                    $altText = $obj->alt_text ?? 'Image'; // Use a fallback if alt text is not available
                //                    $fallbackImage = getFile('default.png');
                //                    return '<img src="' . $imageUrl . '" html="' . $imageUrl . '" onerror="this.src=\'' . $fallbackImage . '\'" class="image-popup avatar avatar-md rounded-circle" style="cursor:pointer;" width="100" height="100" alt="' . $altText . '" loading="lazy">';
                //                })
                ->editColumn('image', function ($obj) {
                    return '<img href="' . getFile($obj->image) . '" src="' . getFile($obj->image) . '" class="image-popup avatar avatar-md rounded-circle" style="cursor:pointer;" width="100" height="100" loading="lazy">';
                })
                ->editColumn('email', function ($obj) {
                    return $obj->email;
                })
                ->editColumn('points', function ($obj) {
                    return $obj->points;
                })
                ->editColumn('status', function ($obj) {
                    return $this->StatusDatatableCustom($obj, StatusEnumObj::ACTIVE->value);
                })
                ->editColumn('city_id', function ($obj) {
                    return ($obj->city_id && $obj->city)
                        ? @$obj->city->title
                        : '';
                })
                ->editColumn('country_id', function ($obj) {
                    return @$obj->country->title ?? 'N/A';
                })
                ->editColumn('wallet', function ($obj) {
                    return $obj->wallet ?? '';
                })
                ->addColumn('action', function ($obj) {
                    $buttons = '';

                    if (Auth::user()->can("update_client_management")) {
                        $buttons .= '
                            <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                                <i class="fa fa-edit"></i>
                            </button>
                        ';
                    }

                    if (Auth::user()->can("delete_client_management")) {
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
            // Pass search values to the view
            $searchValues = [
                'search_name' => $request->search_name,
                'search_email' => $request->search_email,
                // 'search_type' => $request->search_type,
                'search_status' => $request->search_status,
                'search_city_id' => $request->search_city_id,
                'search_country_id' => $request->search_country_id,
                // 'search_level_id' => $request->search_level_id,
            ];

            $countries = $this->countryObj->pluck('title', 'id')->toArray();
            $cities = $this->cityObj->pluck('title', 'id')->toArray();
            // $levels = Level::pluck('title', 'id')->toArray();
            $statuses = StatusEnumObj::cases();

            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => trns($this->route),
                'route' => $this->route,


                'cities' => $cities,
                // 'levels' => $levels,
                'statuses' => $statuses,
                'countries' => $countries
            ]);
        }
    }

    public function show($id)
    {
        // dd($id);
        $client = $this->objModel->where('id', $id)->with('city', 'country', 'courtCases', 'blogLikes', 'blogDislikes', 'blogComments', 'notifications', 'walletTransactions', 'blogReactions')->first();
        return view("{$this->folder}/parts/show", [
            'obj' => $client,
        ]);
    }

    public function create()
    {
        $cities = $this->cityObj->all();
        $countries = $this->countryObj->all();
        $statuses = StatusEnumObj::cases();
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'cities' => $cities,
            'countries' => $countries,
            'statuses' => $statuses,
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Client');
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
        $cities = $this->cityObj->all();
        $countries = $this->countryObj->all();
        $statuses = StatusEnumObj::cases();
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
            'cities' => $cities,
            'countries' => $countries,
            'statuses' => $statuses,
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Client');

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
