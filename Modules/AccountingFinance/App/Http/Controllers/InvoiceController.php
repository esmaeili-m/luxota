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
use Modules\AccountingFinance\App\resources\InvoiceTransactionsResource;
use Modules\AccountingFinance\Services\InvoiceService;
use Modules\AccountingFinance\Services\VoucherService;

class InvoiceController extends Controller
{
    public InvoiceService $service;
    public VoucherService $voucher_service;

    public function __construct(InvoiceService $service,VoucherService $voucher_service)
    {
        $this->service = $service;
        $this->voucher_service = $voucher_service;
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

    public function invoice_transactions($invoice_code)
    {
        return $this->service->get_invoice_transactions($invoice_code);

    }
    public function get_invoice_item_count()
    {
        return $this->service->getCartCount();

    }
    public function index(Request $request)
    {
        $input = $request->only([
            'status',
            'invoice_code',
            'per_page',
            'paginate',
        ]);
        $input['paginate'] = $request->boolean('paginate');
        $categories = $this->service->getInvoices($input);
        return InvoiceResource::collection($categories);
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
    public function get_invoice_with_transaction($invoice_code)
    {
        $invoice= $this->service->get_invoice_with_transaction($invoice_code);
        if (!$invoice){
            return response()->json(['error' => 'Not found'], 404);
        }
        return new InvoiceResource($invoice);

    }

    public function clear_invoice($invoiceId)
    {
        $this->service->clear_invoice($invoiceId);
        return response()->json([
            'success' => true,
            'message' => 'Invoice cleared successfully',
        ], 200);

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
