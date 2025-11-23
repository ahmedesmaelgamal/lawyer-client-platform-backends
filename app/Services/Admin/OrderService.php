<?php

namespace App\Services\Admin;

use App\Enums\OrderStatusEnum;
use App\Models\Lawyer as LawyerObj;
use App\Models\Order as ObjModel;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;

class OrderService extends BaseService
{
    protected string $folder = 'admin/order';
    protected string $route = 'orders';
    protected LawyerObj $lawyerObj;
    public function __construct(ObjModel $objModel,LawyerObj $lawyerObj)
    {
        $this->lawyerObj=$lawyerObj;
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            // $obj = $this->getDataTable();

            // dd($request->all());

            $obj = $this->model->query();

            $obj->when($request->has('search_status') && $request->search_status, function ($query) use ($request) {
                $query->where('status', $request->search_status);
            })
            ->when($request->has('search_lawyer_id') && $request->search_lawyer_id, function ($query) use ($request) {
                $query->where('lawyer_id', $request->search_lawyer_id);
            });

            $obj->get();



            return DataTables::of($obj)
                ->editColumn('lawyer_id', function ($obj) {
                    return @$obj->lawyer->name??"N/A";
                })
                ->editColumn('market_product_id', function ($obj) {
                    return @$obj->marketProduct->title??"N/A";
                })
                ->editColumn('status', function ($obj) {
                    return $obj->status;
                })
                // ->addColumn('action', function ($obj) {
                //     $buttons = '

                //     ';
                //     return $buttons;
                // })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            $statuses=OrderStatusEnum::cases();
            $lawyers=$this->lawyerObj->pluck('name','id')->toArray();
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => trns($this->route),
                'route' => $this->route,
                'statuses'=>$statuses,
                'lawyers'=>$lawyers
            ]);
        }
    }

    public function create()
    {
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Order');
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
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Order');

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
