<?php

namespace App\Services\Admin;

use App\Enums\UserTypeEnum;
use App\Models\WalletTransaction;
use App\Models\WithdrawRequest as ObjModel;
use App\Services\BaseService;
use App\Traits\FirebaseNotification;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class WithdrawRequestService extends BaseService
{
    use FirebaseNotification;
    protected string $folder = 'admin/withdraw_request';
    protected string $route = 'withdraw_requests';

    public function __construct(ObjModel $objModel,protected WalletTransaction $walletTransaction)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    $buttons = '';

                    if (Auth::user()->can("update_wallet_management")) {
                        if ($obj->status == 'pending') {
                            $buttons .= '
                                    <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                                    ' . trns('request_action') . '
                                    <i class="fa fa-wallet"></i>
                                </button>
                                ';
                        }
                    }

                    if (Auth::user()->can("delete_wallet_management")) {
                        if ($obj->status == 'pending') {
                            $buttons .= '
                                    <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                                        data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    ';
                        }
                    }


                    return $buttons;
                })
                ->editColumn('user_id', function ($obj) {
                    return $obj->user ? $obj->user->name : '-';
                })->editColumn('user_type', function ($obj) {
                    return UserTypeEnum::from($obj->user_type)->lang();
                })
                ->editColumn('payment_method', function ($obj) {
                    return trns($obj->payment_method);
                })
                ->editColumn('status', function ($obj) {
                    return '
                        <span title="' . ($obj->reject_reason ?? trns($obj->status)) . '" class="badge badge-' . ($obj->status == 'pending' ? 'warning' : ($obj->status == 'approved' ? 'success' : 'danger')) . '">' . trns($obj->status) . '</span>';
                })
                ->editColumn('created_at', function ($obj) {
                    return $obj->created_at->format('Y-m-d h:i a');
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
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'WithdrawRequest');
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
        if ($data['status'] == 'approved') {
            $oldObj->user->wallet -= $oldObj->amount;
            $oldObj->user->save();

            $this->walletTransaction->create([
                'user_id' => $oldObj->user_id,
                'user_type' => $oldObj->user_type,
                'debit' => 0,
                'credit' => $oldObj->amount,
                'comment' => 'تم سحب المبلغ من المحفظة بنجاح',
            ]);

                $notificationData = [
                    'title' => 'office request rejected',
                    'body' => 'the withdraw request has been approved',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'withdraw_request',
                ];
            if ($oldObj->user_type=='lawyer') {
                $this->sendFcm($notificationData, [$oldObj->user_id],'lawyer_api');
            } elseif($oldObj->user_type=='client') {
                $this->sendFcm($notificationData, [$oldObj->user_id],'client_api');
            }else{
                return "unexpected error happened";
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
