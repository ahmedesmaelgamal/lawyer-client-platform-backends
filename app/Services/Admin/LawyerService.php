<?php

namespace App\Services\Admin;

use App\Enums\LawyerStatusEnum;
use App\Enums\StatusEnum;
use App\Models\City as CityObj;
use App\Models\Country as CountryObj;
use App\Models\Lawyer as ObjModel;
use App\Models\Level as LevelObj;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class LawyerService extends BaseService
{
    protected string $folder = 'admin/lawyer';
    protected string $route = 'lawyers';
    protected CountryObj $countryObj;
    protected LevelObj $levelObj;
    protected ObjModel $objModel;
    protected CityObj $cityObj;
    public function __construct(ObjModel $objModel, LevelObj $levelObj, CountryObj $countryObj, CityObj $cityObj)
    {
        $this->countryObj = $countryObj;
        $this->levelObj = $levelObj;
        $this->objModel = $objModel;
        $this->cityObj = $cityObj;
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            // Debug the request parameters
            // dd($request->search_name);
            // dd($request->all());

            $obj = $this->model->query();

            $obj->when($request->search_name, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search_name . '%');
            })
                ->when($request->has('search_email') && $request->search_email, function ($query) use ($request) {
                    $query->where('email', 'like', '%' . $request->search_email . '%');
                })
                ->when($request->has('search_type') && $request->search_type, function ($query) use ($request) {
                    $query->where('type', 'like', '%' . $request->search_type . '%');
                })
                ->when($request->has('search_status') && $request->search_status, function ($query) use ($request) {
                    $query->where('status', $request->search_status);
                })
                ->when($request->has('search_country_id') && $request->search_country_id, function ($query) use ($request) {
                    $query->where('country_id', $request->search_country_id);
                })
                ->when($request->has('search_city_id') && $request->search_city_id, function ($query) use ($request) {
                    $query->where('city_id', $request->search_city_id);
                })
                ->when($request->has('search_level_id') && $request->search_level_id, function ($query) use ($request) {
                    $query->where('level_id', $request->search_level_id);
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
                //            ->editColumn('image', function ($obj) {
                //                $imageUrl = getFile($obj->image);
                //                $altText = $obj->alt_text ?? 'Image'; // Use a fallback if alt text is not available
                //                $fallbackImage = getFile('default.png');
                //                return '<img src="' . $imageUrl . '" onerror="this.src=\'' . $fallbackImage . '\'" class="image-popup avatar avatar-md rounded-circle" style="cursor:pointer;" width="100" height="100" alt="' . $altText . '" loading="lazy">';
                //            })
                ->editColumn('image', function ($obj) {
                    return '<img href="' . getFile($obj->image) . '" src="' . getFile($obj->image) . '" class="image-popup avatar avatar-md rounded-circle" style="cursor:pointer;" width="100" height="100" loading="lazy">';
                })
                ->editColumn('email', function ($obj) {
                    return $obj->email;
                })
                ->editColumn('status', function ($obj) {
                    return $this->StatusDatatableCustom($obj, StatusEnum::ACTIVE->value);
                })
                ->editColumn('country_id', function ($obj) {
                    return @$obj->country->title;
                })
                ->editColumn('city_id', function ($obj) {
                    return @$obj->city ? $obj->city->title : '';
                })
                ->editColumn('type', function ($obj) {
                    return $obj->type;
                })
                ->editColumn('level_id', function ($obj) {
                    return @$obj->level->title;
                })
                ->editColumn('wallet', function ($obj) {
                    return $obj->wallet ?? '';
                })
                ->addColumn('action', function ($obj) {
                    $buttons = ' ';
                    //                if (Auth::user()->can('update_lawyer_management')) {
                    //                    $buttons .= '
                    //                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                    //                            <i class="fa fa-edit"></i>
                    //                        </button>
                    //                    ';
                    //                }
                    $buttons .= '<a href="' . route('lawyers.show', $obj->id) . '" class="btn btn-pill btn-primary-light" ' . $obj->id . '>
                            <i class="fas fa-eye"></i>
                        </a>  ';
                    if (Auth::user()->can("delete_lawyer_management")) {

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
            // Pass search values to the view
            $searchValues = [
                'search_name' => $request->search_name,
                'search_email' => $request->search_email,
                'search_type' => $request->search_type,
                'search_status' => $request->search_status,
                'search_country_id' => $request->search_country_id,
                'search_city_id' => $request->search_city_id,
                'search_level_id' => $request->search_level_id,
            ];

            $countries = $this->countryObj->pluck('title', 'id')->toArray();
            $cities = $this->cityObj->pluck('title', 'id')->toArray();
            $levels = $this->levelObj->pluck('title', 'id')->toArray();
            $statuses = StatusEnum::cases();
            $names = $this->objModel->pluck('name', 'id')->toArray();
            $emails = $this->objModel->pluck('email', 'id')->toArray();
            $types = LawyerStatusEnum::cases();

            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => trns($this->route),
                'route' => $this->route,
                'cities' => $cities,
                'levels' => $levels,
                'statuses' => $statuses,
                'countries' => $countries,
                'names' => $names,
                'emails' => $emails,
                'types' => $types,
            ]);
        }
    }
    public function create()
    {
        $countries = $this->countryObj->all();
        $cities = $this->cityObj->all();
        $levels = $this->levelObj->all();
        $statuses = StatusEnum::cases();
        $types = LawyerStatusEnum::cases();
        return view("{$this->folder}/parts/create", [
            'createRoute' => route($this->route . '.create'),
            'bladeName' => trns($this->route),
            'route' => $this->route,
            'storeRoute' => route("{$this->route}.store"),
            'statuses' => $statuses,
            'types' => $types,
            'countries' => $countries,
            'cities' => $cities,
            'levels' => $levels,

        ]);
    }


    /**
     * Shows the create view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        // dd($id);
        $lawyer = $this->objModel->where('id', $id)->with('lawyerSpecialities', 'city', 'country', 'level', 'ads', 'courtCaseEvents', 'lawyerPackages', 'walletTransactions', 'lawyerTimes', 'notifications', 'orders', 'blogs', 'blogComments', 'blogCommentReplies')->first();
        // dd($lawyer);
        return view("{$this->folder}/parts/show", [
            'obj' => $lawyer,
        ]);
    }

    /**
     * Handles the storing of the data.
     *
     * @param array $data The data to store.
     *
     * @return \Illuminate\Http\JsonResponse The response.
     */
    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Lawyer');
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
        $levels = $this->levelObj->all();
        $cities = $this->cityObj->all();
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
            'countries' => $countries,
            'levels' => $levels,
            'cities' => $cities
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Lawyer');

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
