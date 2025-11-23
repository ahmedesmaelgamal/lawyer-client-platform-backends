<?php

namespace App\Services\Admin;

use App\Enums\StatusEnum;
use App\Models\MarketProduct as ObjModel;
use App\Models\MarketProductCategory as MarketProductCategory;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class MarketProductService extends BaseService
{
    protected string $folder = 'admin/market_product';
    protected string $route = 'market_products';
    protected MarketProductCategory $marketProductCategory;

    public function __construct(ObjModel $objModel , MarketProductCategory $marketProductCategory)
    {
        $this->marketProductCategory=$marketProductCategory;
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            // $obj = $this->getDataTable();


            $obj = $this->model->query();

            $obj->when($request->search_market_product_category_id, function ($query) use ($request) {
                $query->where('market_product_category_id', 'like', '%' . $request->search_market_product_category_id . '%');
            })
            ->when($request->has('search_status') && $request->search_status, function ($query) use ($request) {
                $query->where('status', $request->search_status);
            });

            $obj->get();



            return DataTables::of($obj)
                ->editColumn('title',function ($obj){
                    return $obj->title;
                })
                ->editColumn('image', function ($obj) {
                    return '<img href="' . getFile($obj->image) . '" src="' . getFile($obj->image) . '" class="image-popup avatar avatar-md rounded-circle" style="cursor:pointer;" width="100" height="100" loading="lazy">';
                })
                // ->editColumn('description',function ($obj){
                //     return $obj->description;
                // })
                // ->editColumn('location',function ($obj){
                //     return $obj->location;
                // })
                ->editColumn('stock',function ($obj){
                    return $obj->stock;
                })

                ->editColumn('price',function ($obj){
                    return $obj->price;
                })
                ->editColumn('market_product_category_id',function ($obj){
                    return @$obj->marketProductCategory->title??'N/A';
                })
                ->editColumn('status',function ($obj){
                    return $this->StatusDatatableCustom($obj,StatusEnum::ACTIVE->value);
                })
                ->addColumn('action', function ($obj) {
                        $buttons = ' ';
                    if (Auth::user()->can("update_market_management")) {

                        $buttons .= '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                    ';
                    }
                    if (Auth::user()->can("delete_market_management")) {

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

            $searchValues = [
                'search_name' => $request->search_name,
                'search_email' => $request->search_email,
                'search_type' => $request->search_type,
                'search_status' => $request->search_status,
                'search_country_id' => $request->search_country_id,
                'search_city_id' => $request->search_city_id,
                'search_level_id' => $request->search_level_id,
            ];

            $marketProductCategories =$this->marketProductCategory->pluck('title', 'id')->toArray();
            $statuses = StatusEnum::cases();
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => trns($this->route),
                'route' => $this->route,
                'marketProductCategories' => $marketProductCategories,
                'statuses' => $statuses
            ]);
        }
    }

    public function create()
    {
        $statuses = StatusEnum::cases();
        $marketProductCategories = $this->marketProductCategory->all();
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'marketProductCategories' => $marketProductCategories,
            'statuses' => $statuses,
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'MarketProduct');
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
        $statuses = StatusEnum::cases();
        $marketProductCategories = $this->marketProductCategory->all();
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
            'marketProductCategories' => $marketProductCategories,
            'statuses' => $statuses,
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'MarketProduct');

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
