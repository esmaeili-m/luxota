<?php

namespace Modules\ActivityLog\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\ActivityLog\App\Models\ActivityLog;
use Modules\ActivityLog\App\resources\ActivityLogResource;
use Modules\ActivityLog\services\ActivityLogService;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public ActivityLogService $service;
    public function __construct(ActivityLogService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        $logs= $this->service->getAll();
        return ActivityLogResource::collection($logs);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activitylog::create');
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
        return view('activitylog::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('activitylog::edit');
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
