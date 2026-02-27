<?php

namespace Modules\Planner\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'task_id' => $this->task_id,

            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'role' => $this->user?->role?->title,
                'avatar' => asset('storage/'.$this->user?->avatar),
            ],

            'parent_id' => $this->parent_id,

            'body' => $this->body,

            'is_edited' => $this->is_edited,
            'is_pinned' => $this->is_pinned,

            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),

            // ریپلای‌ها (اگر لود شده باشند)
            'replies' => CommentResource::collection(
                $this->whenLoaded('replies')
            ),
        ];
    }
}
