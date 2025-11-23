<?php

namespace App\Services\Api\ChatRoom;

use App\Http\Resources\ChatRoomResource;
use App\Services\BaseService;
use App\Models\ChatRoom as ObjModel;
use App\Services\FirestoreService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatRoomService extends BaseService
{
    /**
     * Constructor method
     *
     * @param ObjModel $model
     *
     * @return void
     */
    public function __construct(ObjModel $model,protected FirestoreService $firestoreService)
    {
        parent::__construct($model);
    }

    public function addChatRoom($request)
    {
        if ($this->checkGuard() == 'client_api') {
            $checkChatRoom = $this->model->where('client_id', auth($this->checkGuard())->user()->id)
                ->where('lawyer_id', $request->lawyer_id)
                ->first();
            if ($checkChatRoom) {
                return $this->responseMsg('chat room already exists', new ChatRoomResource($checkChatRoom));
            }
            $chatRoom = $this->model->create([
                'uuid' => Str::uuid(),
                'client_id' => auth($this->checkGuard())->user()->id,
                'lawyer_id' => $request->lawyer_id,
            ]);
            return $this->responseMsg('chat room created successfully', new ChatRoomResource($chatRoom));
        }

        $checkChatRoom = $this->model->where('lawyer_id', auth($this->checkGuard())->user()->id)
            ->where('client_id', $request->client_id)
            ->first();

        if ($checkChatRoom) {
            return $this->responseMsg('chat room already exists', new ChatRoomResource($checkChatRoom));
        }

        $chatRoom = $this->model->create([
            'uuid' => Str::uuid(),
            'client_id' => $request->client_id,
            'lawyer_id' => auth($this->checkGuard())->user()->id
        ]);

        return $this->responseMsg('chat room created successfully', new ChatRoomResource($chatRoom));
    }

    public function getChatRooms()
    {
        if ($this->checkGuard() == 'client_api') {
            $chatRooms = $this->model->where('client_id', auth($this->checkGuard())->user()->id)->get();
            return $this->responseMsg('chat rooms returned successfully', ChatRoomResource::collection($chatRooms)); // new
        }

        $chatRooms = $this->model->where('lawyer_id', auth($this->checkGuard())->user()->id)->get();
        return $this->responseMsg('chat rooms returned successfully', ChatRoomResource::collection($chatRooms));
    }

    public function addMessage($request)
    {
        $validator = $this->apiValidator($request->all(), [
            'chat_id' => 'required',
            'message' => 'required_without:file',
            'file' => 'nullable|file',
        ]);

        if ($validator) {
            return $validator;
        }
        // add message with firestore
        $request->merge([
            'sender_type' => $this->checkGuard() == 'client_api' ? 'client' : 'lawyer',
        ]);


        return $this->firestoreService->addMessage($request);
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
