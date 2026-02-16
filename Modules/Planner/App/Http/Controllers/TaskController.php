<?php

namespace Modules\Planner\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('planner::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        dd($request->all());
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('planner::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('planner::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
