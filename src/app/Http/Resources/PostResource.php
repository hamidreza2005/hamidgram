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
        return [
            'id'=>$this->id,
            'url' => asset($this->url),
            'description' => $this->description,
            $this->mergeWhen(Gate::allows('showLikes',$this->resource),['likes_count' => $this->likes_count]),
            'created_at'=> $this->created_at->toDateTimeLocalString(),
        ];
    }
}
