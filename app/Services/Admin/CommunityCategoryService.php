<?php

namespace App\Services\Admin;

use App\Enums\StatusEnum as StatusEnumObj;
use App\Models\CommunityCategory as CommunityCategoryObj;
use App\Models\CommunityCategory as ObjModel;
use App\Services\BaseService;
use App\Enums\StatusEnum;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CommunityCategoryService extends BaseService
{
    protected string $folder = 'admin/community_category';
    protected string $route = 'community_categories';
    protected CommunityCategoryObj  $communityCategoryObj;
    //    protected StatusEnumObj $statusEnumObj;

    public function __construct(ObjModel $objModel, CommunityCategoryObj $communityCategoryObj)
    {
        parent::__construct($objModel);
        //        $this->statusEnumObj=$statusEnumObj;
        $this->communityCategoryObj = $communityCategoryObj;
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->editColumn('id', function ($obj) {
                    return $obj->id;
                })
                ->editColumn('title', function ($obj) {
                    return $obj->title;
                })
                ->editColumn('status', function ($obj) {
                    return $this->StatusDatatableCustom($obj, StatusEnumObj::ACTIVE->value);
                })
                ->addColumn('action', function ($obj) {
                    $buttons = ' ';
                    if (Auth::user()->can("update_community_management")) {

                        $buttons .= '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                    ';
                    }
                    if (Auth::user()->can("delete_community_management")) {

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
        $communityCategories = $this->communityCategoryObj->all();
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'communityCategories' => $communityCategories,
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'CommunityCategory');
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
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
            'statuses' => $statuses,
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'CommunityCategory');

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
