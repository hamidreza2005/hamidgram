<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Comment;
use App\Http\Resources\CommentResource;
use App\Notifications\CommentNotification;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function add(Request $request , int $postId , int  $parentId = 0)
    {
        $post = Post::findOrFail($postId)->load('user');
        $this->authorize('create',[Comment::class,$post]);
        $data = $request->only('body');
        validateData($data,[
           'body'=>'required|string|min:3'
        ]);
        if ($parentId > 0){
            $parent = Comment::findOrFail($parentId);
        }

        $comment = new Comment();
        $comment->user_id = auth()->id();
        $comment->post_id = $postId;
        $comment->parent_id = $parentId;
        $comment->body = $request->get('body');
        $comment->save();
        if (auth()->id() !== $post->user->id){
            $post->user->notify(new CommentNotification(auth()->user(),$post,$comment));
        }
        return response(['message'=>"Comment Created"],201);
    }

    public function remove($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $this->authorize('delete',$comment);
        foreach ($comment->replies as $reply){
            $reply->delete();
        }
        $comment->delete();
        return response([],204);
    }

    public function update(Request $request , $commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $this->authorize('update',[$comment,$comment->post]);
        validateData($request->only('body'),[
            'body'=>'required|string|min:3'
        ]);
        $comment->body = $request->get('body');
        $comment->save();
        return response(['message'=>'Comment Updated'],203);
    }

    public function view($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $this->authorize('view',[$comment,$comment->post]);
        $output = [
          'comment'=> new CommentResource($comment),
          'replies'=> CommentResource::collection($comment->replies),
        ];
        return response($output,200);
    }
}
