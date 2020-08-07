<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' =>$this->username,
            'avatarUrl' =>$this->avatarUrl ? asset($this->avatarUrl) : null,
            'bio'=>$this->bio,
            'posts'=>$this->whenPivotLoaded('posts',PostResource::collection($this->posts)),
            'posts_count'=>$this->whenPivotLoaded('posts',$this->posts()->count()),
        ];
    }
}
