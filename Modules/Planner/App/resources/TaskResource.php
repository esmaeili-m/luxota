<?php

namespace Modules\Planner\App\resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Support\App\resources\TicketResource;
use Modules\User\App\resources\UserResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            // --- Base Info ---
            'id' => $this->id,
            'task_key' => $this->task_key,
            'task_code' => '#TS'.$this->task_code,

            'title_fa' => $this->title_fa,
            'title_en' => $this->title_en,
            'description' => $this->description,

            // --- Enums ---
            'type' => $this->type,
            'priority' => $this->priority,
            'task_category' => $this->task_category,
            'business_status' =>$this->business_status,
            'has_invoice' => $this->has_invoice,
            'implementation' => $this->implementation,

            // --- Flags ---
            'urgent' => $this->urgent,
            'status' => $this->status,
            'order' => $this->order,

            // --- Dates ---
            'due_date' => $this->due_date,
            'created_at' => $this->created_at?->toDateTimeString(),
            'created_ago' => $this->created_at?->diffForHumans(),
            // --- Computed ---
            'is_overdue' => $this->due_date
                ? now()->gt($this->due_date)
                : false,

            // --- Relations (IMPORTANT) ---
            'board' => new BoardResource(
                $this->whenLoaded('board')
            ),

            'column' => new ColumnResource(
                $this->whenLoaded('column')
            ),

            'sprint' => new SprintResource(
                $this->whenLoaded('sprint')
            ),

            'team' => new TeamResource(
                $this->whenLoaded('team')
            ),

            'logs' => TaskLogResource::collection(
                $this->whenLoaded('logs')
            ),
            'comments' => CommentResource::collection(
                $this->whenLoaded('comments')
            ),
            'tickets' => new TicketResource(
                $this->whenLoaded('tickets')
            ),

            'creator' => $this->whenLoaded('creator', function () {
                return [
                    'name' => $this->creator->name,
                    'avatar' => $this->assignee?->avatar ? asset('/storage/'.$this->assignee?->avatar) : null ,
                    'role' => $this->creator->role?->name,
                ];
            }),
            'ticket' => new TicketResource(
                $this->whenLoaded('ticket')
            ),


            'assignee' => $this->whenLoaded('assignee', function () {
                return [
                    'name' => $this->assignee->name,
                    'avatar' => $this->assignee?->avatar ? asset('/storage/'.$this->assignee?->avatar) : null ,
                    'role' => $this->assignee->role?->name,
                ];
            }),
            'user_times' => UserTimeResource::collection(
                $this->whenLoaded('userTimes')
            ),
            'tags' => TagResource::collection(
                $this->whenLoaded('tags')
            ),

            'parent_task' => new TaskResource(
                $this->whenLoaded('parentTask')
            ),

            // --- Attachments ---
            'attachments' => TaskAttachmentResource::collection(
                $this->whenLoaded('attachments')
            ),
        ];
    }
}
