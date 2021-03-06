<?php

namespace App\Http\Controllers\v1;

use App\Comment;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Jobs\handleUploadedImageJob;
use App\Http\Controllers\Controller;
use App\Like;
use App\Notifications\LikeNotification;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function add(Request $request)
    {
        $data = $request->only(['image','description','just_for_me','show_likes_to_all','comment_status']);
        validateData($data,[
            'image'=>['required','image'],
            'description'=>['string'],
            'just_for_me'=>['boolean'],
            'show_likes_to_all'=>['boolean'],
            'comment_status'=>['boolean'],
        ]);
        $imagePath = '/'.now()->year;
        $image = Storage::putFile($imagePath,$request->image);
        $imagePath = storage_path('app/public/').$image;
        $imageHash = hash_file('sha256',$imagePath);

        if (config("image.processing_in_backend")){
            handleUploadedImageJob::dispatchNow($image);
        }
        $exists = Post::where('hash',$imageHash)->first();
        unset($data['image']);
        $data['url'] = $image;
        $data['hash'] = $imageHash;
        if (!is_null($exists)){
            $data['url'] = $exists->url;
            Storage::delete($image);
        }
        auth()->user()->posts()->create($data);
        return response(['message'=>"Your Post has been created"],201);
    }

    public function remove(Request $request,$id){
        $post = Post::find($id);
        if (is_null($post)){
            return response(['error'=>"Post not found"],404);
        }
        $this->authorize('delete',[$post]);
        $imagePath = $post->url;
        if (Post::where('url',$imagePath)->count() <= 1){
            Storage::delete($imagePath);
        }
        $post->delete();
        return response([],204);
    }

    public function view(Request $request,$id)
    {
        $post = Post::findOrFail($id);
        if (is_null($post)){
            return response(['error'=>'Post Not Found'],404);
        }
        $this->authorize('view',$post);
        if (!Storage::exists($post->url)){
            return response(['error'=>'Post Not Found'],404);
        }
        auth()->user()->views()->create([
            'post_id'=>$post->id
        ]);
        $output = [
          'post'=>new PostResource($post),
          'user'=>new UserResource($post->user),
          'views_count'=> $post->views()->count(),
        ];
        if (Gate::allows('showComments',$post)){
            $output['comments'] = CommentResource::collection($post->comments);
        }
        return response($output,200);
    }

    public function update(Request $request,$id)
    {
        $post = Post::find($id);
        $this->authorize('update',$post);
        $data = $request->only(['description','just_for_me','show_likes_to_all',"comment_status"]);
        validateData($data,[
            'description'=>['string'],
            'just_for_me'=>['boolean'],
            'show_likes_to_all'=>['boolean'],
            'comment_status'=>['boolean'],
        ]);
        $post->update($data);
        return response(['message'=>'Post Updated'],203);
    }

    public function getComments(Request $request,$id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('showComments',$post);
        return response(CommentResource::collection($post->comments),200);
    }

    public function like($postId)
    {
        $post = Post::findOrFail($postId);
        if (Gate::denies('canLike',$post)){
            return response(['error'=>"you Can't Like This post"],400);
        }
        Like::create([
           'post_id' =>$post->id,
           'user_id' =>auth()->id(),
        ]);
        $post->user->notify(new LikeNotification($post,auth()->user()));
        return response(['message'=>"You Liked this Post"],201);
    }

    public function retakeLike($postId)
    {
        $post = Post::findOrFail($postId);
        if (Gate::allows('canLike',$post)){
            return response(['error'=>"you Can't retake your Like"],400);
        }
        Like::query()->where('post_id',$post->id)->where('user_id',auth()->id())->delete();
        return response([],204);
    }
}
