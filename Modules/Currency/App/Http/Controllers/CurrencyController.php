<?php

namespace Modules\Currency\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Currency\App\resources\CurrencyResource;
use Modules\Currency\Services\CurrencyService;

class CurrencyController extends Controller
{
    protected CurrencyService $service;

    public function __construct(CurrencyService $service)
    {
        $this->service = $service;
    }

    /**
     * Get all countries
     */
    public function all(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $countries = $this->service->getActive();
        return CurrencyResource::collection($countries);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('currency::create');
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
        return view('currency::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('currency::edit');
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
