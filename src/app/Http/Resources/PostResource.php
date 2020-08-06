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
        if ($request->has('withUser')){
            $data['user'] = new UserResource($this->user);
        }
        if (Gate::allows('showComments',$this->resource) && $request->has('withComments')){
            $data['comments'] = CommentResource::collection($this->comments);
        }
        return $data;
    }
}
