<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'body'=>$this->resource->body,
            'user'=>new UserResource($this->user),
            'replies'=> $this->whenLoaded('replies',CommentResource::collection($this->replies), new MissingValue()),
            'post'=>$this->whenPivotLoaded('posts',new PostResource($this->post)),
            'created_at'=>$this->created_at->toDateTimeLocalString(),
        ];
    }
}
