<?php

namespace App\Http\Controllers\v1;

use App\Http\Resources\PostResource;
use App\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function explore()
    {
        return response(PostResource::collection(Post::query()->orderByDesc('views_count')->take(20)->get()),200);
    }
}
