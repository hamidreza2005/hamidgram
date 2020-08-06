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
        if ($request->has('withReplies')){
            $data['replies'] = $this->replies;
        }
        if ($request->has('withPost')){
            $data['post'] = $this->post;
        }
        return [
            'body'=>$this->resource->body,
            'user'=>new UserResource($this->user),
            'replies'=> $this->whenLoaded('replies',CommentResource::collection($this->replies), new MissingValue()),
            'created_at'=>$this->created_at->toDateTimeLocalString(),
        ];
    }
}
