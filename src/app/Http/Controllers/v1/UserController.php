<?php

namespace App\Http\Controllers\v1;

use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function profile($username)
    {
        $user = User::where('username',$username)->firstOrFail();
        if (Gate::denies('view',$user)){
            return response(['message'=>"this Account is Private",'user'=>new UserResource($user)],200);
        }
        $output = [
          'user'=>new UserResource($user),
          'posts'=>PostResource::collection($user->posts),
          'followers'=>UserResource::collection($user->followers),
          'following'=>UserResource::collection($user->following),
        ];
        return response($output,200);
    }

    public function delete()
    {
        auth()->user()->delete();
        return response([],204);
    }
}
