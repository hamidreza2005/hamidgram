<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function deleteUser(Request $request , $userId)
    {
        $user = User::findOrFail($userId);
        if(auth()->user()->type != 'admin' && $user->type != 'user'){
            return abort(403);
        }
        $user->tokens()->delete();
        $user->delete();
        return response([],204);
    }

    public function deletepost($postId)
    {
        $post = Post::findOrFail($postId);
        if(auth()->user()->type != 'admin' && $post->user->type != 'user'){
            return abort(403);
        }
        if (Post::query()->where('url',$post->url)->where('id','!=',$post->id)->get()->isEmpty()){
            Storage::delete($post->url);
        }
        $post->delete();
        return response([],204);
    }
}
