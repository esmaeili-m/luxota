<?php

namespace Modules\AccountingFinance\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\AccountingFinance\App\Http\Requests\CreateInvoiceItemRequest;
use Modules\AccountingFinance\App\Http\Requests\CreateInvoiceRequest;
use Modules\AccountingFinance\App\resources\InvoiceItemResource;
use Modules\AccountingFinance\App\resources\InvoiceResource;
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
    public function get_invoice_items()
    {
        $invoiceItem= $this->service->get_invoice_items();
        return InvoiceItemResource::collection($invoiceItem);
    }
    public function invoice_add_item(CreateInvoiceItemRequest $request)
    {
        return $this->service->add_item($request->validated());
    }
    public function invoice_remove_item($id)
    {
        return $this->service->remove_item($id);
    }

    public function get_invoice_item_count()
    {
        return $this->service->getCartCount();

    }
    public function index()
    {
        return view('accountingfinance::index');
    }

    public function store(CreateInvoiceRequest $request): InvoiceResource
    {
        $invoice=$this->service->createInvoice($request->validated());
        return new InvoiceResource($invoice);
    }

    public function get_invoice($invoice_code)
    {
        $invoice= $this->service->get_invoice($invoice_code);
        if (!$invoice){
            return response()->json(['error' => 'Not found'], 404);
        }
        return new InvoiceResource($invoice);

    }

    public function get_invoices_user()
    {
        $invoices=$this->service->get_invoices_user();
        return InvoiceResource::collection($invoices);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('accountingfinance::show');
    }

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
