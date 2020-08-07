<?php

namespace App\Http\Controllers\v1;

use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function profile($username)
    {
        $user = User::where('username',$username)->firstOrFail();
        $output = [
          'user'=>new UserResource($user),
          'posts'=>PostResource::collection($user->posts),
          'followers'=>UserResource::collection($user->followers),
          'following'=>UserResource::collection($user->following),
        ];
        return response($output,200);
    }
}
