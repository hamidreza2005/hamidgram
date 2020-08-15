<?php

namespace App\Http\Controllers\v1;

use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\User;
use App\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use function GuzzleHttp\Psr7\parse_header;

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

    public function getUnreadNotifications()
    {
        $notifications = auth()->user()->unreadNotifications;
        if (is_null($notifications)){
            return response([],200);
        }
        $notifications->each(function ($notification){
           $notification->maskAsRead();
        });
        return response($notifications->pluck('data')->toArray(),200);
    }

    public function getAllNotifications()
    {
        $notifications = auth()->user()->notifications;
        if (!is_null($notifications)){
            $notifications = $notifications->pluck('data')->toArray();
        }
        return response($notifications,200);
    }

    public function changeProfilePicture(Request $request)
    {
        $data = $request->only(['profilePhoto']);
        $validation = Validator::make($data,[
            'profilePhoto'=>['required','image']
        ]);
        if ($validation->fails()){
            return response(['error'=>$validation->errors()],400);
        }
        $filepath = '/'.now()->year;
        $fullPath = '/'.Storage::putFile($filepath,$request->profilePhoto);
        auth()->user()->update([
            'avatarUrl'=>$fullPath
        ]);
        return response(['message'=>"your Profile Picture has been updated"],203);
    }

    public function history()
    {
        $history = PostResource::collection(auth()->user()->views()->getRelation('post')->get());
        return response($history,200);
    }

    public function editProfile(Request $request)
    {
        $this->authorize('update',auth()->user());
        $data = $request->only(['username','bio','is_private','location']);
        $validator = Validator::make($data,[
            'username'=>['string','unique:users,username,'.auth()->id()],
            'bio'=>['string'],
            'is_private'=>['boolean'],
            'avatarUrl'=>['image'],
            'location'=>['string'],
        ]);
        if ($validator->fails()){
            return response(['errors'=>$validator->errors()],400);
        }
        auth()->user()->update($data);
        return response(['message'=>"User Updated"],203);
    }

    public function editSettings(Request $request)
    {
        $this->authorize('update',auth()->user());
        $data = $request->only(['notify_when_get_like','notify_when_get_comment','two_step_verification_status']);
        $validator = Validator::make($data,[
            'notify_when_get_like'=>['boolean'],
            'notify_when_get_comment'=>['boolean'],
            'two_step_verification_status'=>['boolean'],
        ]);
        if ($validator->fails()){
            return response(['errors'=>$validator->errors()],400);
        }
        auth()->user()->setting()->update($data);
        return response(['message'=>'User\'s Setting Updated'],203);
    }

    public function follow(Request $request , $userId)
    {
        $this->authorize('follow',$userId);
        auth()->user()->following()->attach($userId);
        return response(['message'=>"You Followed Requested User"],200);
    }
}
