<?php

namespace App\Http\Controllers\Api\ChatRoom;

use App\Http\Controllers\Controller;
use App\Services\Api\ChatRoom\ChatRoomService;
use Illuminate\Http\Request;

class ChatRoomController extends Controller
{
    public function __construct(protected ChatRoomService $chatRoomService)
    {
    }

    public function addChatRoom(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->chatRoomService->addChatRoom($request);
    }

    public function getChatRooms(): \Illuminate\Http\JsonResponse
    {
        return $this->chatRoomService->getChatRooms();
    }

    public function addMessage(Request $request)
    {
        return $this->chatRoomService->addMessage($request);
    }

}
