<?php

namespace Modules\Planner\Services;

use App\Services\Uploader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Planner\Repositories\BoardRepository;
use Modules\Planner\Repositories\TaskRepository;
use Illuminate\Validation\ValidationException;
use Modules\Support\Repositories\TaskAttachmentRepository;

class TaskService
{
    protected TaskRepository $repo;
    protected BoardRepository $boardRepository;
    protected TaskRepository $taskRepository;
    protected TaskAttachmentRepository $taskAttachmentRepository;

    public function __construct(TaskRepository $repo,BoardRepository $boardRepository,TaskRepository $taskRepository,TaskAttachmentRepository $taskAttachmentRepository)
    {
        $this->repo = $repo;
        $this->boardRepository = $boardRepository;
        $this->taskRepository = $taskRepository;
        $this->taskAttachmentRepository = $taskAttachmentRepository;
    }
    public function getTasks(array $filters)
    {
        $perPage = $filters['per_page'] ?? 15;
        $page = $filters['page'] ?? 1;
        $paginate = $filters['paginate'] ?? true;

        $allowedWith = [
            'board',
            'column',
            'sprint',
            'team',
            'ticket',
            'creator',
            'creator.role',
            'assignee',
            'assignee.role',
            'attachments',
            'parentTask',
            'children',
        ];

        $with = [];

        if (!empty($filters['with'])) {

            $requested = collect(explode(',', $filters['with']))
                ->map(fn($item) => trim($item))
                ->filter()
                ->toArray();

            $with = array_values(array_intersect($requested, $allowedWith));
        }

        // Pass $with به Repository
        return $this->repo->getTasks(
            filters: $filters,
            with: $with,
            perPage: $perPage,
            page: $page,
            paginate: $paginate
        );
    }


    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            $files=$data['attachments'] ?? [];
            $titles=$data['attachments_titles'] ?? [];
            $board = $this->boardRepository->find($data['board_id']);
            if (!$board) {
                throw ValidationException::withMessages(['board_id' => 'Board not found.']);
            }
            if ($board->type === 'scrum' && empty($data['sprint_id'])) {
                throw ValidationException::withMessages(['sprint_id' => 'Sprint is required for Scrum boards.']);
            }

            // --- آماده سازی TaskData ---
            $taskData = [
                'board_id'        => $data['board_id'],
                'column_id'       => $data['column_id'],
                'sprint_id'       => $data['sprint_id'] ?? null,
                'ticket_id'       => $data['ticket_id'] ?? null,
                'task_code'       => $this->getMaxTaskCode(),
                'title_fa'        => $data['title_fa'],
                'title_en'        => $data['title_en'] ?? null,
                'description'     => $data['description'] ?? null,
                'type'            => $data['type'],
                'priority'        => $data['priority'] ?? 'low',
                'task_category'   => $data['task_category'] ?? null,
                'urgent'          => $data['urgent'] ?? false,
                'parent_task_id'  => $data['parent_task_id'] ?? null,
                'business_status' => $data['business_status'] ?? null,
                'has_invoice'     => $data['has_invoice'] ?? null,
                'implementation'  => $data['implementation'] ?? null,
                'assigned_to'     => $data['assigned_to'] ?? null,
                'due_date'        => $data['due_date'] ?? null,
                'team_id'         => $data['team_id'] ?? null,
                'created_by'      => Auth::id(),
            ];

            // --- ایجاد Task از طریق Repository ---
            $task = $this->taskRepository->create($taskData);

            // --- تولید و آپدیت task_key ---
            $taskKey = $this->generateTaskKey($board->key, $task->id);
            $task = $this->taskRepository->updateTaskKey($task, $taskKey);

            // --- ذخیره Attachments ---
            foreach ($files ?? [] as $index => $file) {
                $title = $titles[$index] ?? $file->getClientOriginalName();

                $this->taskAttachmentRepository->create([
                    'task_id'    => $task->id,
                    'file_name'  => $file->getClientOriginalName(),
                    'file_path'  => Uploader::uploadImage($file, 'planner'),
                    'file_type'  => $file->getClientMimeType(),
                    'file_size'  => $file->getSize(),
                    'title'      => $title,
                    'status'     => true,
                    'uploaded_by'=> Auth::id(),
                ]);
            }

            return $task;
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $task = $this->taskRepository->find($id);
            if (!$task) {
                throw ValidationException::withMessages(['task_id' => 'Task not found.']);
            }

            $board = $this->boardRepository->find($data['board_id'] ?? $task->board_id);
            if (!$board) {
                throw ValidationException::withMessages(['board_id' => 'Board not found.']);
            }

            if ($board->type === 'scrum' && empty($data['sprint_id'] ?? $task->sprint_id)) {
                throw ValidationException::withMessages(['sprint_id' => 'Sprint is required for Scrum boards.']);
            }

            // --- آپدیت فیلدهای اصلی ---
            $taskData = [
                'board_id'        => $data['board_id'] ?? $task->board_id,
                'column_id'       => $data['column_id'] ?? $task->column_id,
                'sprint_id'       => $data['sprint_id'] ?? $task->sprint_id,
                'ticket_id'       => $data['ticket_id'] ?? $task->ticket_id,
                'title_fa'        => $data['title_fa'] ?? $task->title_fa,
                'title_en'        => $data['title_en'] ?? $task->title_en,
                'description'     => $data['description'] ?? $task->description,
                'type'            => $data['type'] ?? $task->type,
                'priority'        => $data['priority'] ?? $task->priority,
                'task_category'   => $data['task_category'] ?? $task->task_category,
                'urgent'          => $data['urgent'] ?? $task->urgent,
                'parent_task_id'  => $data['parent_task_id'] ?? $task->parent_task_id,
                'business_status' => $data['business_status'] ?? $task->business_status,
                'has_invoice'     => $data['has_invoice'] ?? $task->has_invoice,
                'implementation'  => $data['implementation'] ?? $task->implementation,
                'assigned_to'     => $data['assigned_to'] ?? $task->assigned_to,
                'due_date'        => $data['due_date'] ?? $task->due_date,
                'team_id'         => $data['team_id'] ?? $task->team_id,
                'status'          => $data['status'] ?? $task->status,
            ];

            $task = $this->taskRepository->update($task, $taskData);

            // --- مدیریت Attachments ---

            $files = $data['attachments'] ?? [];
            $titles = $data['attachments_titles'] ?? [];
            $deletedIds = $data['deleted_attachments'] ?? []; // برای فایل‌هایی که کاربر حذف کرده
            $titlesUpdate = $data['attachments_titles_update'] ?? [];

            foreach ($titlesUpdate as $id => $title) {
                $attachment = $this->taskAttachmentRepository->find($id);
                if ($attachment) {
                    $this->taskAttachmentRepository->update($attachment, ['title' => $title]);
                }
            }
            // حذف فایل‌های مشخص شده
            if (!empty($deletedIds)) {
                $this->taskAttachmentRepository->deleteByIds($deletedIds);
            }

            // ذخیره فایل‌های جدید
            foreach ($files as $index => $file) {
                $title = $titles[$index] ?? $file->getClientOriginalName();

                $this->taskAttachmentRepository->create([
                    'task_id'    => $task->id,
                    'file_name'  => $file->getClientOriginalName(),
                    'file_path'  => Uploader::uploadImage($file, 'planner'),
                    'file_type'  => $file->getClientMimeType(),
                    'file_size'  => $file->getSize(),
                    'title'      => $title,
                    'status'     => true,
                    'uploaded_by'=> Auth::id(),
                ]);
            }

            return $task;
        });
    }

    public function delete($id)
    {
        $task = $this->repo->find($id);
        if (!$task) {
            return false;
        }
        return $this->repo->delete($task);
    }
    /**
     * تولید task_key انسانی مثل DEV-102
     */
    protected function generateTaskKey(string $boardKey, int $taskId): string
    {
        return strtoupper($boardKey) . '-' . $taskId;
    }
    private function getMaxTaskCode()
    {
        return $this->taskRepository->getMaxCode();
    }

}
