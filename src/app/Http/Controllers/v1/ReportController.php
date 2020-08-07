<?php

namespace App\Http\Controllers\v1;

use App\Jobs\ReportPostJob;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request , $postId)
    {
        $post = Post::findOrFail($postId);
        $validation = Validator::make($request->only(['reason']),[
            'reason'=>"required|string"
        ]);
        if ($validation->fails()){
            return response(["error"=>$validation->errors()],400);
        }
        if (!Cache::has('report_'.auth()->id().$post->id)){
            ReportPostJob::dispatch($post,auth()->user(),$request->reason);
        }
        return response(['message'=>'Your Report Has been submit'],200);
    }
}
