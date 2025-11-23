<?php

namespace App\Services\Admin;

use App\Enums\StatusEnum;
use App\Models\MarketOffer as ObjModel;
use App\Models\MarketProduct as MarketProductObj;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class MarketOfferService extends BaseService
{
    protected string $folder = 'admin/market_offer';
    protected string $route = 'market_offers';
    protected MarketProductObj $marketProductObj;

    public function __construct(ObjModel $objModel, MarketProductObj $marketProduct)
    {
        $this->marketProductObj = $marketProduct;
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->editColumn('image', function ($obj) {
                    return $this->imageDataTable($obj->image);
                })
                // storage_path($obj->image)
                ->editColumn('image', function ($obj) {
                    return '<img href="' . getFile($obj->image) . '" src="' . getFile($obj->image) . '" class="image-popup avatar avatar-md rounded-circle" style="cursor:pointer;" width="100" height="100" loading="lazy">';
                })
                ->editColumn('market_product_id', function ($obj) {
                    return @$obj->marketProduct->title ?? "N/A";
                })
                ->editColumn('from', function ($obj) {
                    return $obj->from;
                })
                ->editColumn('to', function ($obj) {
                    return $obj->to;
                })
                ->editColumn('status', function ($obj) {
                    return $this->StatusDatatableCustom($obj, StatusEnum::ACTIVE->value);
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
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => trns($this->route),
                'route' => $this->route,
            ]);
        }
    }

    public function create()
    {
        $statuses = StatusEnum::cases();
        $marketProducts = $this->marketProductObj->all();
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'marketProducts' => $marketProducts,
            'statuses' => $statuses
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'MarketOffer');
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
        $marketProducts = $this->marketProductObj->all();
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
            'marketProducts' => $marketProducts,
            'statuses' => $statuses
        ]);
    }

    public function update($data, $marketOffer)
    {
        $oldObj = $this->getById($marketOffer);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'MarketOffer');

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

    public function getLatestActiveOffers()
    {
        return $this->model->active()->latest()->get();
    }
}
