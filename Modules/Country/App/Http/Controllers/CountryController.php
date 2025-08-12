<?php

namespace Modules\Country\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Country\App\resources\CountryResource;
use Modules\Country\Services\CountryService;

class CountryController extends Controller
{
    protected CountryService $service;

    public function __construct(CountryService $service)
    {
        $this->service = $service;
    }

    /**
     * Get all countries
     */
    public function all(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $countries = $this->service->getActive();
        return CountryResource::collection($countries);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = $this->service->getPaginated();
        return CountryResource::collection($countries);


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('country::create');
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
        return view('country::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('country::edit');
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
        $country = $this->service->find($id);
        $country->delete();
        return response()->json(['message' => 'Country moved to trash successfully']);
    }
    public function toggle_status($id)
    {
        $category = $this->service->toggle_status($id);
        return response()->json(['message' => 'Change Status successfully']);
    }

    /**
     * Display a listing of trashed countries.
     */
    public function trash()
    {
        $countries = $this->service->getTrashed();
        return CountryResource::collection($countries);
    }

    /**
     * Restore the specified country from trash.
     */
    public function restore($id)
    {
        $country = $this->service->restore($id);
        return response()->json(['message' => 'Country restored successfully']);
    }

    /**
     * Permanently delete the specified country.
     */
    public function forceDelete($id)
    {
        $this->service->forceDelete($id);
        return response()->json(['message' => 'Country permanently deleted']);
    }
}
