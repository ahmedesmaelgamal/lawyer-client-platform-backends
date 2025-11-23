<?php

namespace App\Services\Admin;

use App\Enums\ExpireEnum;
use App\Enums\StatusEnum;
use App\Models\LawyerReport as ObjModel;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;


class LawyerReportsServices extends BaseService
{
    protected string $folder = 'admin/Lawyer_Report';
    protected string $route = 'Lawyer_Report';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->editColumn('lawyer_id', function ($obj) {
                    return @$obj->lawyer?->name ?? "-";
                })
                ->editColumn('client_id', function ($obj) {
                    return @$obj->client?->name ?? "-";
                })
                ->editColumn('status', function ($obj) {
                    return $this->StatusDatatableCustom($obj, StatusEnum::ACTIVE->value);
                })
                ->editColumn("body", function($obj) {
                    $shortBody = Str::limit($obj->body, 50);
                    return '<span class="text-primary cursor-pointer" 
                                id="report-'.$obj->id.'" 
                                data-body="'.e($obj->body).'" 
                                onclick="showFullBody('.$obj->id.')">
                                '.$shortBody.'
                            </span>';
                })

                ->rawColumns(['body']) 
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'bladeName' => trns($this->route),
                'route' => $this->route,
            ]);
        }
    }

   
}
