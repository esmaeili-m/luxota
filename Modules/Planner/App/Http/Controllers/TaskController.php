<?php

namespace Modules\Planner\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Planner\App\Http\Requests\CreateTaskRequest;
use Modules\Planner\App\Http\Requests\UpdateTaskRequest;
use Modules\Planner\App\resources\TaskResource;
use Modules\Planner\Services\TaskService;

class TaskController extends Controller
{
    public TaskService $service;

    public function __construct(TaskService $service)
    {
        $this->service=$service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $input = $request->only([
            'status',
            'content',
            'with',
            'parent_id',
        ]);
        $input['paginate'] = $request->boolean('paginate', true);
        $input['page'] = $request->input('page', 1);
        $input['per_page'] = $request->input('per_page', 10);

        $tasks = $this->service->getTasks($input);
        return TaskResource::collection($tasks);
    }

    public function store(CreateTaskRequest $request)
    {
        $task=$this->service->create($request->all());
        $task->load([
            'board',
            'ticket',
            'column',
            'creator',
            'assignee',
            'attachments',
        ]);
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $task = $this->service->update($id, $request->validated());
        if (!$task) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $task = $this->service->delete($id);
        if (!$task) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json(['message' => 'Deleted successfully']);
    }
}
