<?php

namespace App\Http\Controllers\v1;

use App\Comment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function add(Request $request , int $postId , int  $parentId = 0)
    {
        $post = Post::findOrFail($postId);
        $this->authorize('create',$post);
        $data = $request->only('body');
        $validation = Validator::make($data,[
           'body'=>'required|string|min:3'
        ]);
        if ($validation->fails()){
            return response(['error'=>$validation->errors()],400);
        }
        if ($parentId > 0){
            $parent = Comment::findOrFail($parentId);
        }

        $comment = new Comment();
        $comment->user_id = auth()->id();
        $comment->post_id = $postId;
        $comment->parent_id = $parentId;
        $comment->body = $request->get('body');
        $comment->save();
        return response(['message'=>"Comment Created"],201);
    }
}
