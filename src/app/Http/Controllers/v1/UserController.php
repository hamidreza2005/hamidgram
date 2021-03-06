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
        $user = auth()->user();
        $user->tokens()->delete();
        $user->delete();
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
        validateData($data,[
            'profilePhoto'=>['required','image']
        ]);
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
        validateData($data,[
            'username'=>['string','unique:users,username,'.auth()->id()],
            'bio'=>['string'],
            'is_private'=>['boolean'],
            'location'=>['string'],
        ]);
        auth()->user()->update($data);
        return response(['message'=>"User Updated"],203);
    }

    public function editSettings(Request $request)
    {
        $this->authorize('update',auth()->user());
        $data = $request->only(['notify_when_get_like','notify_when_get_comment','two_step_verification_status']);
        validateData($data,[
            'notify_when_get_like'=>['boolean'],
            'notify_when_get_comment'=>['boolean'],
            'two_step_verification_status'=>['boolean'],
        ]);
        auth()->user()->setting()->update($data);
        return response(['message'=>'User\'s Setting Updated'],203);
    }

    public function follow(Request $request , $userId)
    {
        $user = User::findOrFail($userId);
        $this->authorize('follow',$user);
        auth()->user()->following()->attach($user->id);
        return response(['message'=>"You Followed Requested User"],200);
    }

    public function unfollow(Request $request , $userId){
        $user = User::findOrFail($userId);
        $this->authorize('unFollow',$user);
        auth()->user()->following()->detach($user->id);
        return response(['message'=>"You Unfollowed Requested User"],200);
    }

    public function search(Request $request)
    {
        if (!$request->has('q')){
            return response([],200);
        }
        $users = UserResource::collection(User::query()->where('username',"LIKE","%{$request->get('q')}%")->get());
        return response($users,200);
    }
}
