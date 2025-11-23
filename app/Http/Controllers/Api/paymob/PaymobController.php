<?php

namespace App\Http\Controllers\Api\paymob;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymobRequest as ObjRequest;
use App\Models\Paymob as ObjModel;
use App\Services\PaymobService as ObjService;
use Illuminate\Http\Request;

class PaymobController extends Controller
{
    public function __construct(protected ObjService $objService)
    {
    }

    public function pay(Request $request)
    {
        $price = $request->input('price');
        return $this->objService->generatePaymentUrl($price);
    }

    public function paymentCallBack(Request $request)
    {
        $orderId = $request->input('orderId');
        return $this->objService->checkPaymentStatus($orderId);
    }
}
