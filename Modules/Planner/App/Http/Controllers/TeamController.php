<?php

namespace Modules\Planner\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Planner\App\resources\TeamResource;
use Modules\Planner\Services\TeamService;

class TeamController extends Controller
{
    protected TeamService $service;

    public function __construct(TeamService $service)
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

        $teams = $this->service->getTeams($input);
        return TeamResource::collection($teams);
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
        //
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
