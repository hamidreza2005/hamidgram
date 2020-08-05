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
        $data = [
            'url' => asset($this->url),
            'description' => $this->description,
        ];
        if (Gate::allows('showLikes',$this->resource)){

        }
        return $data;
    }
}
