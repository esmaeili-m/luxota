<?php

namespace Modules\AccountingFinance\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\AccountingFinance\App\Http\Requests\CreateInvoiceItemRequest;
use Modules\AccountingFinance\Services\InvoiceService;

class InvoiceController extends Controller
{
    public InvoiceService $service;

    public function __construct(InvoiceService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function invoice_add_item(CreateInvoiceItemRequest $request)
    {
        return $this->service->add_item($request->validated());
    }
    public function invoice_remove_item($id)
    {
        return $this->service->remove_item($id);
    }
    public function index()
    {
        return view('accountingfinance::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accountingfinance::create');
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
        return view('accountingfinance::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('accountingfinance::edit');
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
