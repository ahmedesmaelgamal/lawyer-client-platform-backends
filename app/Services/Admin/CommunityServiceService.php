<?php

namespace App\Services\Admin;

use App\Enums\StatusEnum as StatusEnumObj;
use App\Models\CommunityCategory as CommunityCategoryObj;
use App\Models\CommunityService as ObjModel;
use App\Models\CommunitySubCategory as CommunitySubCategoryObj;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class CommunityServiceService extends BaseService
{
    protected string $folder = 'admin/community_service';
    protected string $route = 'community_services';
//    protected StatusEnumObj $statusEnumObj;
    protected CommunityCategoryObj $communityCategoryObj;
    protected CommunitySubCategoryObj $communitySubCategoryObj;


    public function __construct(ObjModel $objModel, CommunityCategoryObj $communityCategoryObj, CommunitySubCategoryObj $communitySubCategoryObj)
    {
        parent::__construct($objModel);
//        $this->statusEnumObj=$statusEnumObj;
        $this->communityCategoryObj = $communityCategoryObj;
        $this->communitySubCategoryObj = $communitySubCategoryObj;
    }

    public function index($request)
    {

        if ($request->ajax()) {
// dd($request->all());
            $obj = $this->model->query();
// dd($obj);
            $obj->when($request->search_community_category_id, function ($query) use ($request) {
                $query->whereHas('communitySubCategory', function ($subQuery) use ($request) {
                    $subQuery->where('community_category_id', $request->search_community_category_id);
                });
            })
                ->when($request->has('search_community_sub_category_id') && $request->search_community_sub_category_id, function ($query) use ($request) {
                    $query->where('community_sub_category_id', 'like', '%' . $request->search_community_sub_category_id . '%');
                })
                ->when($request->has('search_status') && $request->search_status, function ($query) use ($request) {
                    $query->where('status', $request->search_status);
                });

            $obj->get();

            return DataTables::of($obj)
                ->editColumn('community_category_id', function ($obj) {
                    $communityCategory = @$this->communityCategoryObj->where('id', $obj->communitySubCategory->CommunityCategory->id)->first();
                    return @$communityCategory->title??'N/A';
                })
                ->editColumn('community_sub_category_id', function ($obj) {
                    return @$obj->communitySubCategory?->title??'N/A';
                })
                ->editColumn('body', function ($obj) {
                    return '<span class="span-body-show" title="' . $obj->body . '" data-body="' . $obj->body . '">' . Str::limit($obj->body, 20) . '</span>';
                })
                ->editColumn('status', function ($obj) {
                    return @$this->StatusDatatableCustom($obj, StatusEnumObj::ACTIVE->value)??'N/A';
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
            $searchValues = [
                'search_community_category_id' => $request->search_community_category_id,
                'search_community_sub_category_id' => $request->search_community_sub_category_id,
                'search_status' => $request->search_status,

            ];

            $communityCategories = $this->communityCategoryObj?->pluck('title', 'id')->toArray();
            $communitySubCategories = $this->communitySubCategoryObj?->pluck('title', 'id')->toArray();
            $statuses = StatusEnumObj::cases();

            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => trns($this->route),
                'route' => $this->route,
                'communityCategories' => $communityCategories,
                'communitySubCategories' => $communitySubCategories,
                'statuses' => $statuses,
            ]);
        }
    }

    public function create()
    {
        $communitySubCategories = $this->communitySubCategoryObj->all();
        $statuses = StatusEnumObj::cases();
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'communitySubCategories' => $communitySubCategories,
            'statuses' => $statuses,
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'CommunityService');
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
        $communitySubCategories = $this->communitySubCategoryObj->all();
        $communityCategories = $this->communityCategoryObj->all();
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
            'statuses' => $statuses,
            'communityCategories' => $communityCategories,
            'communitySubCategories' => $communitySubCategories,
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'CommunityService');

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
