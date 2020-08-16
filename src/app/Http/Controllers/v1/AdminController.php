<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\Void_;

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
        $this->checkUserIfNotAdmin($user);
        $user->tokens()->delete();
        $user->delete();
        return response([],204);
    }

    public function deletepost($postId)
    {
        $post = Post::findOrFail($postId);
        $this->checkUserIfNotAdmin($post->user);
        if (Post::query()->where('url',$post->url)->where('id','!=',$post->id)->get()->isEmpty()){
            Storage::delete($post->url);
        }
        $post->delete();
        return response([],204);
    }

    public function raiseUser(Request $request,$userId){
        $data = $request->only(['type']);
        validateData($data,[
            'type'=>['required',Rule::in(['manager','admin','user'])]
        ]);
        $user = User::findOrFail($userId);
        $user->type = $data['type'];
        $user->save();
        return response(["message"=>"User type Changed"],203);
    }

    public function editUser(Request $request,$userId){
        $user = User::query()->findOrFail($userId);
        $this->checkUserIfNotAdmin($user);
        $data =$request->only(['avatarUrl','bio','username','location']);
        validateData($data,[
            'username'=>['string','unique:users,username,'.$user->id],
            'bio'=>['string'],
            'is_private'=>['boolean'],
            'location'=>['string'],
        ]);
        $user->update($data);
        return response('User Updated',203);
    }

    protected function checkUserIfNotAdmin(User $user){
        if(auth()->user()->type != 'admin' && $user->type != 'user'){
            return abort(403);
        }
    }
}
