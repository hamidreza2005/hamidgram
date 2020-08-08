<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

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
            'location'=>$this->location ?? new MissingValue(),
//            'posts'=>$this->whenPivotLoaded('posts',PostResource::collection($this->posts)),
//            'posts_count'=>$this->whenLoaded('posts',$this->posts()->count()),
//            'followers'=>$this->whenLoaded('followers',self::collection($this->followers)),
//            'following'=>$this->whenLoaded('following',self::collection($this->following)),
        ];
    }
}
