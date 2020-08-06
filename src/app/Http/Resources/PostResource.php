<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($request->has('withUser')){
            $data['user'] = new UserResource($this->user);
//            $data['views_count'] = $this->views()->count();
        }
        if (Gate::allows('showComments',$this->resource) && $request->has('withComments')){
            $data['comments'] = CommentResource::collection($this->comments);
        }
        return [
            'url' => asset($this->url),
            'description' => $this->description,
            'created_at'=> $this->created_at->toDateTimeLocalString(),
            'views_count'=> $this->whenLoaded('views',$this->views()->count()),
            'user'=>$this->whenLoaded('user',new UserResource($this->user)),
            'comments_count'=>$this->whenLoaded('comments',$this->comments()->count()),
        ];
    }
}
