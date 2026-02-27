<?php

namespace Modules\Planner\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Planner\App\Http\Requests\CreateTimeEstimateRequest;
use Modules\Planner\Services\TimeEstimateService;

class TimeEstimateController extends Controller
{
    public TimeEstimateService $service;

    public function __construct(TimeEstimateService $service)
    {
        $this->service =$service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('planner::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('planner::create');
    }


    public function store(CreateTimeEstimateRequest $request)
    {
        $this->service->syncTimes($request->validated());
        return response()->json([
            'message' => 'Times synced successfully.'
        ]);
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
