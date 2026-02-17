<?php

namespace Modules\Support\Repositories;

use Modules\Planner\App\Models\TaskAttachment;

class TaskAttachmentRepository
{
    public function create(array $data): TaskAttachment
    {
        return TaskAttachment::create($data);
    }
    public function deleteByIds(array $ids): void
    {
        TaskAttachment::whereIn('id', $ids)->delete();
    }

    public function findByTaskId(int $taskId)
    {
        return TaskAttachment::where('task_id', $taskId)->get();
    }
}
