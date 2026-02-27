<?php

namespace Modules\Planner\Services;

use App\Services\Uploader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Planner\App\Models\Task;
use Modules\Planner\Repositories\BoardRepository;
use Modules\Planner\Repositories\ColumnRepository;
use Modules\Planner\Repositories\TaskLogRepository;
use Modules\Planner\Repositories\TaskRepository;
use Illuminate\Validation\ValidationException;
use Modules\Support\Repositories\TaskAttachmentRepository;

class TaskService
{
    protected TaskRepository $repo;
    protected BoardRepository $boardRepositorysitory;
    protected TaskRepository $taskRepository;
    protected TaskAttachmentRepository $taskAttachmentRepository;
    protected TaskLogService $taskLogService;
    protected ColumnRepository $columnRepo;

    public function __construct(TaskRepository $repo,ColumnRepository $columnRepo,BoardRepository $boardRepositorysitory,TaskRepository $taskRepository,TaskAttachmentRepository $taskAttachmentRepository,TaskLogService $taskLogService)
    {
        $this->repo = $repo;
        $this->boardRepositorysitory = $boardRepositorysitory;
        $this->taskLogService = $taskLogService;
        $this->taskRepository = $taskRepository;
        $this->taskAttachmentRepository = $taskAttachmentRepository;
        $this->columnRepo = $columnRepo;
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
            'tags',
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
            $board = $this->boardRepositorysitory->find($data['board_id']);
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

            $task = $this->taskRepository->create($taskData);

            $taskKey = $this->generateTaskKey($board->key, $task->id);
            $task = $this->taskRepository->updateTaskKey($task, $taskKey);
            $task->tags()->sync($data['tags']);
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
            $this->taskLogService->createTaskLog($task, 'created');
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

            $board = $this->boardRepositorysitory->find($data['board_id'] ?? $task->board_id);
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
            $oldBoard  = $task->board;
            $oldColumn = $task->column;

            $newBoardId  = $taskData['board_id'];
            $newColumnId = $taskData['column_id'];
            $boardChanged  = $oldBoard->id  != $newBoardId;
            $columnChanged = $oldColumn->id != $newColumnId;
            $task = $this->taskRepository->update($task, $taskData);
            if ($boardChanged || $columnChanged) {
                $this->taskLogService->moveTaskLog(
                    $task,
                    $oldBoard,
                    $oldColumn
                );
            }
            $task->tags()->sync($data['tags']);

            $files = $data['attachments'] ?? [];
            $titles = $data['attachments_titles'] ?? [];
            $deletedIds = $data['attachments_deleted'] ?? [];
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

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function getByCode($code,$with=[])
    {
        $allowedWith = [
            'board',
            'column',
            'userTimes',
            'tags',
            'sprint',
            'comments.user',
            'comments.replies',
            'team',
            'team.users',
            'ticket',
            'ticket.user',
            'creator',
            'creator.role',
            'assignee',
            'assignee.role',
            'attachments',
            'parentTask',
            'children',
        ];
        if (!empty($with)) {

            $requested = collect(explode(',', $with))
                ->map(fn($item) => trim($item))
                ->filter()
                ->toArray();

            $with = array_values(array_intersect($requested, $allowedWith));
        }

        return $this->repo->getByCode($code,$with);
    }
    public function delete($id)
    {
        $task = $this->repo->find($id);
        if (!$task) {
            return false;
        }
        return $this->repo->delete($task);
    }

    public function move(int $taskId, string $direction)
    {
        $task = $this->taskRepository->find($taskId);
        if (!$task) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $oldBoardId = $task->board_id;
        $oldColumnId = $task->column_id;

        if ($direction === 'forward') {
            $nextColumn = $this->columnRepo->getNextColumn($task);

            if ($nextColumn) {
                $newBoardId = $nextColumn->board_id;
                $newColumnId = $nextColumn->id;
            } else {
                $nextBoard = $this->boardRepository->getNextBoard($task->board_id);
                if (!$nextBoard) return $task; // برد بعدی وجود ندارد
                $firstColumn = $this->columnRepo->getFirstColumn($nextBoard);
                $newBoardId = $nextBoard->id;
                $newColumnId = $firstColumn->id;
            }
        } elseif ($direction === 'backward') {
            $prevColumn = $this->columnRepo->getPreviousColumn($task);

            if ($prevColumn) {
                $newBoardId = $prevColumn->board_id;
                $newColumnId = $prevColumn->id;
            } else {
                $prevBoard = $this->boardRepository->getPreviousBoard($task->board_id);
                if (!$prevBoard) return $task; // برد قبلی وجود ندارد
                $lastColumn = $this->columnRepo->getLastColumn($prevBoard);
                $newBoardId = $prevBoard->id;
                $newColumnId = $lastColumn->id;
            }
        } else {
            throw new \InvalidArgumentException("Direction must be 'forward' or 'backward'");
        }
        $this->update($task->id,['border_id'=>$newBoardId,'column_id'=>$newColumnId]);

        return $task;
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
