<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\Notification;

class FirebaseService
{
    protected function fcmUrl()
    {
        return "https://fcm.googleapis.com/v1/projects/ataaby-c45d7/messages:send";
    }

    public function sendFcm($data, $user_ids = [], $user_type = 'client', $additionalData = [])
    {
        $apiUrl = $this->fcmUrl();
        $accessToken = $this->getAccessToken();

        if ($user_ids && $user_type == 'client') {
            $deviceTokens = DeviceToken::whereIn('user_id', $user_ids)->where('user_type', 'client')->pluck('token')->toArray();
        } elseif ($user_ids && $user_type == 'lawyer_api') {
            $deviceTokens = DeviceToken::whereIn('user_id', $user_ids)->where('user_type', 'lawyer')->pluck('token')->toArray();
        } else {
            $deviceTokens = DeviceToken::pluck('token')->toArray();
        }

        foreach ($user_ids as $user_id) {
            Notification::create([
                'title' => $data['title'],
                'body' => $data['body'],
                'user_id' => $user_id,
                'user_type' => $user_type,
            ]);
        }

        $responses = [];
        foreach ($deviceTokens as $token) {
            $payload = $this->preparePayload($data, $token, $additionalData);
            $responses[] = $this->sendNotification($apiUrl, $accessToken, $payload);
        }

        return response()->json(['responses' => $responses]);
    }

    protected function getAccessToken()
    {
        $credentialsFilePath = public_path('firebase.json'); // Update this line
        $credentials = json_decode(file_get_contents($credentialsFilePath), true);

        $payload = [
            'iss' => $credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => $credentials['token_uri'],
            'exp' => time() + 3600,
            'iat' => time(),
        ];

        $header = ['alg' => 'RS256', 'typ' => 'JWT'];

        $jwtHeader = base64_encode(json_encode($header));
        $jwtPayload = base64_encode(json_encode($payload));
        $signature = base64_encode(hash_hmac('sha256', "$jwtHeader.$jwtPayload", $credentials['private_key'], true));

        return "$jwtHeader.$jwtPayload.$signature";
    }

    protected function preparePayload($data, $token, $additionalData)
    {
        if (is_object($additionalData) && method_exists($additionalData, 'toArray')) {
            $additionalData = $additionalData->toArray();
        }

        if (isset($additionalData['from'])) {
            $additionalData['from_custom'] = $additionalData['from'];
            unset($additionalData['from']);
        }

        array_walk_recursive($additionalData, function (&$item) {
            $item = (string)$item;
        });

        return json_encode([
            'message' => [
                'notification' => [
                    'title' => $data['title'],
                    'body' => $data['body'],
                ],
                'data' => $additionalData,
                'token' => $token,
            ],
        ]);
    }

    protected function sendNotification($url, $accessToken, $payload)
    {
        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post($url, [
                'headers' => [
                    "Authorization" => "Bearer " . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'body' => $payload,
            ]);

            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
