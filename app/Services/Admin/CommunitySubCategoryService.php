<?php

namespace App\Services\Admin;

use App\Enums\StatusEnum;
use App\Models\CommunityCategory;
use App\Models\CommunitySubCategory as ObjModel;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CommunitySubCategoryService extends BaseService
{
    protected string $folder = 'admin/community_sub_category';
    protected string $route = 'community_sub_categories';
    protected ObjModel $objModel;

    public function __construct(ObjModel $objModel,protected CommunityCategory $communityCategory)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
//            dd($obj);
            return DataTables::of($obj)
                ->editColumn('community_category_id', function ($obj) {
                    return $obj->communityCategory ? @$obj->communityCategory->title : '';
                })
                ->editColumn('status', function ($obj) {
                    return $this->StatusDatatableCustom($obj,StatusEnum::ACTIVE->value);
                })
                ->editColumn('title',function ($obj) {
                    return $obj->title;
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
        $statuses = StatusEnum::cases();
        $communityCategories = $this->communityCategory->all();
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'statuses' => $statuses,
            'community_categories' => $communityCategories
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'SubCategory');
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
        $communityCategories = $this->objModel->all();
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
            'statuses' => $statuses,
            'communityCategories' => $communityCategories,
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'SubCategory');

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
