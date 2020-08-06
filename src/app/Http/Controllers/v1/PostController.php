<?php

namespace App\Http\Controllers\v1;

use App\Http\Resources\PostResource;
use App\Jobs\handleUploadedImageJob;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function add(Request $request)
    {
        $data = $request->only(['image','description','just_for_me','show_likes_to_all','comment_status']);
        $validation = Validator::make($data,[
            'image'=>['required','image'],
            'description'=>['string'],
            'just_for_me'=>['boolean'],
            'show_likes_to_all'=>['boolean'],
            'comment_status'=>['boolean'],
        ]);
        if ($validation->fails()){
            return response(['error'=>$validation->errors()],400);
        }
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
        $post = Post::find($id);
        if (is_null($post)){
            return response(['error'=>'Post Not Found'],404);
        }
        $this->authorize('view',$post);
        if (!Storage::exists($post->url)){
            return response(['error'=>'Post Not Found'],404);
        }
        return response(new PostResource($post),200);
    }

    public function update(Request $request,$id)
    {
        $post = Post::find($id);
        $this->authorize('update',$post);
        $data = $request->only(['description','just_for_me','show_likes_to_all',"comment_status"]);
        $validation = Validator::make($data,[
            'description'=>['string'],
            'just_for_me'=>['boolean'],
            'show_likes_to_all'=>['boolean'],
            'comment_status'=>['boolean'],
        ]);
        if ($validation->fails()){
            return response(['error'=>$validation->errors()],400);
        }
        $post->update($data);
        return response(['message'=>'Post Updated'],203);
    }
}
