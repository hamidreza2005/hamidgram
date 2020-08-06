<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        $data = [
          'body'=>$this->body,
          'user'=>$this->user
        ];
        if ($request->has('withReplies')){
            $data['replies'] = $this->replies;
        }
        if ($request->has('withPost')){
            $data['post'] = $this->post;
        }
        return $data;
    }
}
