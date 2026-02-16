<?php

namespace Modules\Planner\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Planner\App\Http\Requests\CreateSprintRequest;
use Modules\Planner\App\resources\SprintResource;
use Modules\Planner\Services\SprintService;

class SprintController extends Controller
{
    protected SprintService $service;

    public function __construct(SprintService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $input['paginate'] = $request->boolean('paginate', true);
        $input['page'] = $request->input('page', 1);
        $input['per_page'] = $request->input('per_page', 10);

        $sprints = $this->service->getSprints($input);
        return SprintResource::collection($sprints);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(CreateSprintRequest $request)
    {
        $sprint= $this->service->create($request->validated());
        return new SprintResource($sprint);
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
