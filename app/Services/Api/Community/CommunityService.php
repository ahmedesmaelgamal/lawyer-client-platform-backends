<?php

namespace App\Services\Api\Community;

use App\Http\Resources\PostCommentReplyResource;
use App\Http\Resources\PostCommentResource;
use App\Http\Resources\PostResource;
use App\Models\BlogComment;
use App\Models\BlogCommentReply;
use App\Services\BaseService;
use App\Models\Blog as ObjModel;
use Illuminate\Support\Facades\Auth;

class CommunityService extends BaseService
{
    public function __construct(ObjModel $model, protected BlogComment $blogComment,protected BlogCommentReply $blogCommentReply)
    {
        parent::__construct($model);
    }

    public function getPosts()
    {
        return $this->responseMsg('get posts successfully', PostResource::collection($this->model->orderBy('created_at', 'desc')->paginate(10)));
    }

    public function getPostComments($id)
    {
        $post = $this->model->findOrFail($id);
        return $this->responseMsg('get post comments successfully', PostCommentResource::collection($post->blogComments));
    }

    public function getCommentReply($id)
    {
        $blog = $this->blogComment->findOrFail($id);
        return $this->responseMsg('get comment replies successfully', PostCommentReplyResource::collection($blog->replies()->where('reply_id', null)->get()));
    }
    public function getReplyReplies($id)
    {
        $reply = $this->blogCommentReply->findOrFail($id);
        return $this->responseMsg('get comment replies successfully', PostCommentReplyResource::collection($reply->replies));
    }

    public function addPost($request)
    {
        if (!$this->checkGuard()) {
            return $this->responseMsg('Invalid Token Check Your Token', null, 422);
        }

        $validator = $this->apiValidator($request->all(), [
            'body' => 'required_if:files,null',
            'files' => 'nullable|array',
        ]);

        if ($validator) {
            return $validator;
        }


        $newPost = new $this->model();
        $newPost->body = $request->body;
        $newPost->user_id = Auth::guard($this->checkGuard())->user()->id;
        $newPost->user_type = $this->checkGuard() == 'client_api' ? 'client' : 'lawyer';

        if ($newPost->save()) {
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileExtension = $file->getClientOriginalExtension();
                    $newPost->files()->create([
                        'file' => $this->handleFile($file, 'blogs'),
                        'type' => $fileExtension
                    ]);
                }
            }
        }

        return $this->responseMsg('add post successfully', PostResource::make($newPost));
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

    public function addPostAction($request)
    {
        if (!$this->checkGuard()) {
            return $this->responseMsg('Invalid Token Check Your Token', null, 422);
        }

        $validator = $this->apiValidator($request->all(), [
            'blog_id' => 'required|exists:blogs,id',
            'reaction' => 'nullable|in:like,dislike',
        ]);

        if ($validator) {
            return $validator;
        }

        $blog = $this->model->find($request->blog_id);

        $blogReaction = $blog->blogReactions()->where('user_id', Auth::guard($this->checkGuard())->user()->id)->first();

        if ($blogReaction) {
            //delete same reaction
            if ($blogReaction->reaction == $request->reaction) {
                $blogReaction->delete();
                $request->reaction == 'like' ? $blog->count_like -= 1 : $blog->count_dislike -= 1;
                $blog->save();
                return $this->responseMsg('reaction removed successfully');
            } else {
                $request->reaction == 'like' ? ($blog->count_dislike -= 1 && $blog->count_like += 1) : ($blog->count_dislike += 1 && $blog->count_like -= 1);
                $blog->save();
                $blogReaction->update([
                    'reaction' => $request->reaction
                ]);
            }
        }else{
            $blog->blogReactions()->create([
                'user_id' => Auth::guard($this->checkGuard())->user()->id,
                'user_type' => $this->checkGuard() == 'client_api' ? 'client' : 'lawyer',
                'reaction' => $request->reaction
            ]);

            $request->reaction == 'like' ? $blog->count_like += 1 : $blog->count_dislike += 1;
            $blog->save();
        }

        return $this->responseMsg('reaction added successfully');
    }

    public function addPostComment($request)
    {
        if (!$this->checkGuard()) {
            return $this->responseMsg('Invalid Token Check Your Token', null, 422);
        }

        $validator = $this->apiValidator($request->all(), [
            'blog_id' => 'required|exists:blogs,id',
            'comment' => 'required',
        ]);

        if ($validator) {
            return $validator;
        }
        $blog = $this->model->find($request->blog_id);

        $blog->blogComments()->create([
            'comment' => $request->comment,
            'user_id' => Auth::guard($this->checkGuard())->user()->id,
            'user_type' => $this->checkGuard() == 'client_api' ? 'client' : 'lawyer',
        ]);

        return $this->responseMsg('comment added successfully', PostCommentResource::collection($blog->blogComments));
    }

    public function deletePost($id)
    {
        $this->model->findOrFail($id)->delete();
        return $this->responseMsg('post deleted successfully');
    }

    public function deletePostComment($id)
    {
        $this->blogComment->findOrFail($id)->delete();
        return $this->responseMsg('comment deleted successfully');
    }

    public function deletePostCommentReply($id)
    {
        $this->blogCommentReply->findOrFail($id)->delete();
        return $this->responseMsg('comment Reply deleted successfully');
    }

    public function addPostCommentReply($request)
    {
        if (!$this->checkGuard()) {
            return $this->responseMsg('Invalid Token Check Your Token', null, 422);
        }

        $validator = $this->apiValidator($request->all(), [
            'comment_id' => 'required|exists:blog_comments,id',
            'reply_id' => 'nullable|exists:blog_comment_replies,id',
            'reply' => 'required',
        ]);

        if ($validator) {
            return $validator;
        }

        $comment = $this->blogComment->find($request->comment_id);

        $comment->replies()->create([
            'reply' => $request->reply,
            'reply_id' => $request->reply_id,
            'user_id' => Auth::guard($this->checkGuard())->user()->id,
            'user_type' => $this->checkGuard() == 'client_api' ? 'client' : 'lawyer',
        ]);

        return $this->responseMsg('comment added successfully', PostCommentReplyResource::collection($comment->replies()->where('comment_id', $request->comment_id)->where('reply_id', null)->get()));

    }
}
