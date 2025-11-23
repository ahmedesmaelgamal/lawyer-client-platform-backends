<?php

namespace App\Http\Controllers\Api\Community;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Api\Community\CommunityService;

class CommunityController extends Controller
{
    public function __construct(protected CommunityService $communityService)
    {
    }

    public function getPosts()
    {
        return $this->communityService->getPosts();
    }

    public function getPostComments($id)
    {
        return $this->communityService->getPostComments($id);
    }

    public function getCommentReply($id)
    {
        return $this->communityService->getCommentReply($id);
    }
    public function getReplyReplies($id)
    {
        return $this->communityService->getReplyReplies($id);
    }

    public function addPost(Request $request)
    {
        return $this->communityService->addPost($request);

    }

    public function addPostAction(Request $request)
    {
        return $this->communityService->addPostAction($request);

    }

    public function addPostComment(Request $request)
    {
        return $this->communityService->addPostComment($request);

    }

    public function deletePostComment($id)
    {
        return $this->communityService->deletePostComment($id);
    }

    public function deletePostCommentReply($id)
    {
        return $this->communityService->deletePostCommentReply($id);
    }

    public function deletePost($id)
    {
        return $this->communityService->deletePost($id);
    }

    public function addPostCommentReply(Request $request)
    {
        return $this->communityService->addPostCommentReply($request);

    }
}
