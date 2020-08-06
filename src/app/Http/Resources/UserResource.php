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
        $data = [
            'username' =>$this->username,
            'avatarUrl' =>$this->avatarUrl ? asset($this->avatarUrl) : null,
            'bio'=>$this->bio
        ];
        return $data;
    }
}
