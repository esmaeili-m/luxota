<?php

namespace Modules\Planner\Repositories;

use Modules\Planner\App\Models\Task;

class TaskRepository
{
    public function getTasks(array $filters = [], $perPage = 15, $page = 1, $paginate = true, array $with = [])
    {
        $query = Task::query();
        if (isset($with)) {
            $query->with($with);
        }
        if (!empty($filters)) {
            $query->search($filters);
        }

        return $paginate
            ? $query->paginate($perPage, ['*'], 'page', $page)
            : $query->get();
    }

    public function create($data)
    {
        return Task::create($data);
    }
    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }
    public function find($id): ?Task
    {
        return Task::find($id);
    }
    public function getMaxCode()
    {
        $max = Task::max('task_code');
        return $max ? $max + 1 : 1000;
    }
    public function updateTaskKey(Task $task, string $taskKey): Task
    {
        $task->task_key = $taskKey;
        $task->save();
        return $task;
    }

    public function delete(Task $task)
    {
        return $task->delete();
    }

}
