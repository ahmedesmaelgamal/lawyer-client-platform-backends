<?php

namespace App\Services\Admin;

use App\Enums\AdConfirmationEnum;
use App\Enums\StatusEnum;
use App\Enums\StatusEnum as StatusEnumObj;
use App\Models\Ad as ObjModel;
use App\Models\Lawyer as LawyerObj;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class AdService extends BaseService
{
    protected string $folder = 'admin/ads';
    protected string $route = 'ads';
//    protected StatusEnumObj $statusEnumObj;
    protected LawyerObj $lawyerObj;

    public function __construct(
        ObjModel  $objModel,
//        StatusEnumObj $statusEnumObj,
        LawyerObj $lawyerObj
    )
    {
//        $this->statusEnumObj=$statusEnumObj;
        $this->lawyerObj = $lawyerObj;
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $status = $request->status;
            $adConfirmation = $request->ad_confirmation;
            $obj = $this->model->with('lawyer')->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })->when($adConfirmation, function ($query) use ($adConfirmation) {
                return $query->where('ad_confirmation', $adConfirmation);
            })->latest()->get();
            // dd($obj);
            return DataTables::of($obj)
                ->editColumn('lawyer_id', function ($obj) {
//                    dd($obj->lawyer);
                    return $obj->lawyer ? $obj->lawyer->name : 'no lawyer associated';
                })
                ->editColumn('package_id', function ($obj) {
                    return $obj->offerPackage ? $obj->offerPackage->title : '';
                })
                ->editColumn('status', function ($obj) {
                    if ($obj->ad_confirmation == 'rejected' || $obj->ad_confirmation == 'requested') {
                        return '
                                <div class="form-check form-switch disabled">
                                    <input style="margin-left: 0px;" class="tgl tgl-ios form-check-input" type="checkbox" disabled />
                                    <label class="tgl-btn" dir="ltr" for="statusUser-' . $obj->id . '"></label>
                                </div>';
                    }
                    return $this->StatusDatatableCustom($obj, StatusEnumObj::ACTIVE->value);

                })
                ->editColumn('ad_confirmation', function ($obj) {
//                    $this->StatusDatatableCustom($obj, StatusEnumObj::ACTIVE->value);
                    return $this->acceptOrReject($obj);
                })
                ->editColumn('from_date', function ($obj) {
                    return $obj->from_date;
                })
                ->editColumn('to_date', function ($obj) {
                    return $obj->to_date;
                })
                ->editColumn('image', function ($obj) {
                    return '<img href="' . getFile($obj->image) . '" src="' . getFile($obj->image) . '" class="image-popup avatar avatar-md rounded-circle" style="cursor:pointer;" width="100" height="100" loading="lazy">';
                })
                ->addColumn('action', function ($obj) {
                    if (Auth::user()->can("delete_ad_management")) {

                        $buttons = '

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
                'adConfirmation' => route("{$this->route}.confirm"),
                'route' => $this->route,
            ]);
        }
    }

    public function create()
    {
        $statuses = StatusEnumObj::cases();
        $lawyers = $this->lawyerObj->all();
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'statuses' => $statuses,
            'lawyers' => $lawyers,
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Ads');
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
        $statuses = StatusEnumObj::cases();
        $lawyers = $this->lawyerObj->all();
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
            'statuses' => $statuses,
            'lawyers' => $lawyers,
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Ads');

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

    public function updateColumnSelectedForConfirmation($request)
    {
//        dd($request->all());
        try {
            $oldObj = $this->getById($request->id);
            if ($request->status ==AdConfirmationEnum::REJECTED->value) {
                $oldObj->update(['ad_confirmation' => $request->status, 'refuse_reason' => $request->refuse_reason]);
            }elseif ($request->status == AdConfirmationEnum::CONFIRMED->value) {
                $oldObj->update(['ad_confirmation' => $request->status,'status'=>StatusEnum::ACTIVE->value]);
            }
            return response()->json(['status' => 200, 'message' => trns('Data updated successfully.')]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => trns('Something went wrong.'), trns('error') => $e->getMessage()]);
        }

    }
}
