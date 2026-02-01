<?php

namespace Modules\Price\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Price\App\Http\Requests\CreatePriceRequest;
use Modules\Price\App\resources\PriceResource;
use Modules\Price\Services\PriceService;
use Modules\Product\Services\ProductService;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public PriceService $service;
    public function __construct(PriceService $service)
    {
        $this->service = $service;
    }
    public function index($id)
    {
        return $this->service->getProductPrices($id);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('price::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePriceRequest $request): PriceResource
    {
        $data= $request->only(['zone_id','price','product_id']);
        $prices= $this->service->createOrUpdate($data);
        return new PriceResource($prices);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('price::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('price::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): JsonResponse
    {
        $this->service->update($request->all());

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json(['message' => 'Price deleted successfully']);
    }
}
