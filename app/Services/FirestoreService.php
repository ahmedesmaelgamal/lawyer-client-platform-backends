<?php

namespace App\Services;

use App\Models\ChatRoom;
use App\Models\Lawyer;
use GuzzleHttp\Client;
use App\Models\Client as ClientModel;
use Illuminate\Support\Facades\Log;

class FirestoreService extends BaseService
{
    protected Client $client;
    protected mixed $projectId;

    public function __construct(protected ClientModel $user, protected Lawyer $lawyer, protected ChatRoom $chatRoom, protected FirebaseService $firebaseService)
    {
        $this->client = new Client([
            'base_uri' => 'https://firestore.googleapis.com/v1/projects/' . env('FIREBASE_PROJECT_ID') . '/databases/(default)/documents/',
            'verify' => false,
        ]);

        $this->projectId = env('FIREBASE_PROJECT_ID');
    }

    public function addMessage($request): \Illuminate\Http\JsonResponse
    {
        try {
            $chatRoom = $this->chatRoom->where('uuid', $request->chat_id)->first();
            $fileUrl = '';
            if ($request->sender_type == 'lawyer') {
                $fromUser = auth()->guard('lawyer_api')->user();
                $toUser = $this->user->where('id', $chatRoom->client_id)->first();
            } else {
                $fromUser = auth()->guard('client_api')->user();
                $toUser = $this->lawyer->where('id', $chatRoom->lawyer_id)->first();
            }

            $fromUserType = $request->sender_type;
            $toUserType = $request->sender_type == 'lawyer' ? 'client' : 'lawyer';

            // Check if the user has a file
            if ($request->has('file')) {
                $file = $this->handleFile($request->file('file'), 'messages');
                $fileUrl = getFile($file);
            }

            $documentName = "rooms/" . $request->chat_id . "/messages/"; // تحديد الـ document ID بناءً على معرف المستخدم

            $time = now()->toIso8601String();

            $response = $this->client->post($documentName, [
                'json' => [
                    'fields' => [
                        'senderId' => ['integerValue' => $fromUser->id],
                        'receiverId' => ['integerValue' => $toUser->id],
                        'senderType' => ['stringValue' => $fromUserType], // Add sender type
                        'receiverType' => ['stringValue' => $toUserType], // Add receiver type
                        'bodyMessage' => ['stringValue' => $request->message ?? ''],
                        'chatId' => ['stringValue' => $request->chat_id],
                        'fileUrl' => ['stringValue' => $fileUrl],
                        'seen' => ['booleanValue' => false],
                        'time' => ['timestampValue' => $time],
                    ]
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if (!$result) {
                return response()->json(['success' => false, 'message' => 'Failed to send message'], 500);
            }

            try {
                // Send notification to the user
                $data = [
                    'title' => 'رسالة جديدة',
                    'body' => $request->message,
                ];
                $this->firebaseService->sendFcm($data, [$toUser->id], $toUserType, ['chat_id' => $request->chat_id]);
            } catch (\Exception $exception) {
                Log::error('Notification sending failed: ' . $exception->getMessage());
            }

            return $this->responseMsg('تم إرسال الرسالة بنجاح ');
        } catch (\Exception $e) {
            Log::error('Error sending message: ' . $e->getMessage());
            return $this->responseMsg('هناك مشكلة ما لا يمكن تحديدها في الوقت الحالي');
        }
    }
}
