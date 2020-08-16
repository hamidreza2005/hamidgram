<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('adminAndManager');
        $this->middleware('admin')->only(['raiseUser']);
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

    public function raiseUser(Request $request,$userId){
        $data = $request->only(['type']);
        $validator = Validator::make($data,[
            'type'=>['required',Rule::in(['user','manager','admin'])]
        ]);
        $user = User::findOrFail($userId);
        $user->type = $data['type'];
        $user->save();
        return response(["message"=>"User type Changed"],203);
    }
}
