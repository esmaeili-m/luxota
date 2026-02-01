<?php

namespace Modules\Permission\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Permission\App\resources\PermissionResource;
use Modules\Permission\Services\PermissionService;

class PermissionController extends Controller
{
    public PermissionService $service;

    public function __construct(PermissionService $service)
    {
        $this->service= $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions=$this->service->get_permissions();
        return PermissionResource::collection($permissions);
    }

    public function getGroupedPermissions()
    {
        $permissions=$this->service->getGroupedPermissions();
        return PermissionResource::collection($permissions);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permission::create');
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
        return view('permission::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('permission::edit');
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
