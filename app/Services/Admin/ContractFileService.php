<?php

namespace App\Services\Admin;

use App\Models\ContractCategory;
use App\Models\ContractFile;
use App\Models\ContractFile as ObjModel;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ContractFileService extends BaseService
{
    protected string $folder = 'admin/contract_file';
    protected string $route = 'contract_files';

    public function __construct(ObjModel $objModel, protected ContractCategory $contractCategory , protected ContractFile $contractFile)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {

//        $contractCategoryId = $this->contractCategory->find($id);
//        dd($contractCategoryId);
//        $contractCategory = $this->model->with('contractCategory')->where('contract_category_id', $contractCategoryId);
//dd($contractCategory->get());
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->addColumn('file_extension', function ($obj) {
                    return $obj->file_extension;
                })
                ->addColumn('file_name', function ($obj) {
                    return $obj->file_name;
                })
                ->addColumn('file', function ($obj) {
                    return getFile($obj->file_path);
                })
                ->addColumn('action', function ($obj) {
                    $buttons = '';

                    if (Auth::user()->can("update_contract_files_management")) {
                        $buttons .= '
                            <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                                <i class="fa fa-edit"></i>
                            </button>
                        ';
                    }

                    if (Auth::user()->can("delete_contract_files_management")) {
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
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => trns($this->route),
                'route' => $this->route,
//                'contractCategory' => $contractCategory->first()
            ]);
        }
    }


    public function showContracts($request, $id)
    {
        if ($request->ajax()) {
            $obj = $this->contractFile->where('contract_category_id', $id)->get();
            return DataTables::of($obj)
                ->addColumn('file_extension', function ($obj) {
                    return $obj->file_extension;
                })
                ->addColumn('file_name', function ($obj) {
                    return $obj->file_name;
                })
                ->addColumn('file', function ($obj) {
                    return '<a href="'.getFile($obj->file_path).'" target="_blank">
                                <span class="text-center"><i class="fas fa-file-pdf text-danger"></i> '.trns('show_file').' </span>
                            </a>';
                })
                ->addColumn('action', function ($obj) {
                    $buttons = '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
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
//                'contractCategory' => $contractCategory->first()
            ]);
        }
    }


    public function create()
    {
        $contractCategories = $this->contractCategory->find(request('id'));
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'contractCategory' => $contractCategories,
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['file_path'])) {
            $fileData = $this->storeFiles($data['file_path'], 'ContractFile');
            $data['file_path'] = $fileData['file_path'];
            $data['file_extension'] = $fileData['file_extension'];
            $data['uploaded_file_name'] = $fileData['file_name'];
        }

        try {
            $this->model->create([
                'file_name' => [
                    'ar' => $data['file_name']['ar'],
                    'en' => $data['file_name']['en'],
                ],
                'file_extension' => $data['file_extension'],
                'file_path' => $data['file_path'],
                'contract_category_id' => $data['contract_category_id'],
            ]);

            return response()->json([
                'status' => 200,
                'message' => trns('Data created successfully.')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => trns('Something went wrong.'),
                trns('error') => $e->getMessage()
            ]);
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
            $data['image'] = $this->handleFile($data['image'], 'ContractFile');

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
