<?php

namespace Modules\Product\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return[
            'title'=>$this->title,
            'description' => $this->description,
            'score' => $this->score,
            'created_at'=> $this->created_at->format('M d, Y'),
            'user' => $this->whenLoaded('user',function (){
                return [
                  'id' => $this->user->id,
                  'name' => $this->user->name,
                  'avatar' => $this->user->avatar,
                ];
            }),
            'children' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
