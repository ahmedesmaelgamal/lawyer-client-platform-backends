<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\Notification;
use App\Models\PaymentLog as ObjModel;
use App\Models\WalletTransaction;
use App\Traits\FirebaseNotification;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymobService
{

    use FirebaseNotification;
    public function __construct(protected ObjModel $model, protected WalletTransaction $walletTransaction)
    {
    }

    public function generatePaymentUrl($total_price)
    {
        try {
            $user = auth($this->checkGuard())->user();
            // Get user data
            $name = $user->name;

            if (is_array($name)) {
                $name = explode(' ', $name);
            }

            $first_name = is_array($name) ? ($name[0] ?? $name) : $name;
            $last_name = is_array($name) ? ($name[1] ?? $name) : $name;
            $phone = $user->phone != null ? $user->country_code . $user->phone : null;

            // Step 1: Get Authentication Token
            $authClient = new Client([
                'base_uri' => 'https://accept.paymob.com/api/auth/tokens',
                'timeout' => 50,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    "username" => config('services.paymob.username'),
                    "password" => config('services.paymob.password')
                ]

            ]);

            $authResponse = $authClient->request('POST');
            $authData = json_decode($authResponse->getBody()->getContents());


            // Step 2: Create Payment Link
            $paymentClient = new Client([
                'base_uri' => 'https://accept.paymob.com/api/ecommerce/payment-links',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $authData->token
                ],
                'json' => array_merge([
                    "amount_cents" => (int)($total_price * 100),
                    "currency" => "EGP",
                    "is_live" => false,
                    "payment_methods" => [
                        config('services.paymob.integration_id')
                    ],
                    "full_name" => $first_name . ' ' . $last_name,
                    "email" => auth()->user()->email ?? 'email@example.com',
                    "phone_number" => $phone ?? '+20123456789',
                ])
            ]);

            $paymentResponse = $paymentClient->request('POST');
            $paymentData = json_decode($paymentResponse->getBody()->getContents(), true);


            // Step 3: Create Order


            return [
                'success' => true,
                'payment_url' => $paymentData['client_url'],
                'order_id' => $paymentData['order']
            ];

        } catch (\Exception $e) {
            Log::error('PayMob Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public function checkPaymentStatus($orderId)
    {
        $user = auth($this->checkGuard())->user();


        try {
            $client = new Client([
                'base_uri' => 'https://accept.paymob.com/api/',
                'timeout' => 5,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);

            // Authenticate and get the token
            $authResponse = $client->request('POST', 'auth/tokens', [
                'json' => [
                    "username" => config('services.paymob.username'),
                    "password" => config('services.paymob.password')
                ]
            ]);

            $authData = json_decode($authResponse->getBody()->getContents());

            // Transaction inquiry
            $inquiryResponse = $client->request('POST', 'ecommerce/orders/transaction_inquiry', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $authData->token
                ],
                'json' => [
                    "order_id" => $orderId
                ]
            ]);

            $inquiryData = json_decode($inquiryResponse->getBody()->getContents(), true);


            $status = '';
            if ($inquiryData['is_refunded'] == true) {
                $comment = 'the payment is refunded';
                $status = 'refunded';
            } elseif ($inquiryData['pending'] == true) {
                $comment = 'the payment is pending';
                $status = 'pending';
            } elseif ($inquiryData['pending'] == false) {
                if ($inquiryData['success'] == true) {

                    $status = 'success';
                    $comment = 'the payment is completed';


                    $data = [
                        'title' => 'payment success',
                        'body' => 'payment has been completed successfully',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'payment',
//                        'model' => 'court_cases',
//                        'module_id' => $addNewPrivateCase->id,
                    ];
                    $this->sendFcm($data, [$user->id], $this->checkGuard()=='lawyer_api'??'client_api');


                } else {
                    $status = 'failed';
                    $comment = 'the payment has failed';
                    $data = [
                        'title' => 'payment success',
                        'body' => 'payment has has failed',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'payment',
//                        'model' => 'court_cases',
//                        'module_id' => $addNewPrivateCase->id,
                    ];
                    $this->sendFcm($data, [$user->id], $this->checkGuard()=='lawyer_api'??'client_api');

                }
            }
            $checkPayment = $this->model->where('payment_id', $inquiryData['order']['id'])
                ->where('payment_status', '=', 'success')->exists();
            if (!$checkPayment) {
                $this->model->create([
                    'payment_id' => $inquiryData['order']['id'],
                    'log' => json_encode($inquiryData),
                    'payment_amount' => $inquiryData['amount_cents'] / 100,
                    'payment_status' => $status,
                ]);
                if ($inquiryData['success'] == true) {
                    $this->walletTransaction->create([
                        'user_id' => $user->id,
                        'user_type' => $this->checkGuard() == 'client_api' ? 'client' : 'lawyer',
                        'debit' => $inquiryData['amount_cents'] / 100,
                        'credit' => 0,
                        'comment' => 'تم شحن المحفظة بنجاح بقيمة :' . $inquiryData['amount_cents'] / 100,
                    ]);

                    $user->wallet += $inquiryData['amount_cents'] / 100;
                    $user->save();
                }
            }

            return $inquiryData;


        } catch (\Exception $e) {
            Log::error("Error checking payment status: " . $e->getMessage());
            return false;
        }
    }

    protected function checkGuard()
    {
        // Check if the client guard is authenticated
        if (Auth::guard('client_api')->check()) {
            return 'client_api'; // Return the guard name
        }

        // Check if the lawyer guard is authenticated
        if (Auth::guard('lawyer_api')->check()) {
            return 'lawyer_api'; // Return the guard name
        }

        // If neither guard is authenticated, throw an exception or return false
        return false;
    }
}
