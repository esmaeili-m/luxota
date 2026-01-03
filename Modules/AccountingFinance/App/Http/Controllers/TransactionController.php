<?php

namespace Modules\AccountingFinance\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\AccountingFinance\App\Http\Requests\CreateTransactionItemRequest;
use Modules\AccountingFinance\App\resources\TransactionItemResource;
use Modules\AccountingFinance\App\resources\TransactionResource;
use Modules\AccountingFinance\Services\TransactionService;
use Modules\AccountingFinance\Services\VoucherService;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public TransactionService $service;
    public VoucherService $voucherService;

    public function __construct(TransactionService $service,VoucherService $voucherService)
    {
        $this->service=$service;
        $this->voucherService=$voucherService;
    }
    public function get_transactions_user_wallet()
    {
        $trasncations=$this->service->get_transactions_user_wallet();
        return TransactionResource::collection($trasncations)->additional(['label' => ['name' => 'Wallet']]);
    }

    public function get_transactions_user($voucher_id)
    {
        $trasncations=$this->service->get_transactions_user($voucher_id);
        $voucher=$this->voucherService->getById($voucher_id);
        return TransactionResource::collection($trasncations)->additional(['label' => ['name' => $voucher?->title]]);

    }

    public function create_tranaction_item(CreateTransactionItemRequest $request)
    {
        $data=$request->validated();
        $invoiceId=$this->service->validateBalance(
            $request->validated(),
            auth()->id()
        );
        unset($data['invoice_code']);
        $data['invoice_id']=$invoiceId;
        $transaction_item=$this->service->create_tranaction_item($data);
        return new TransactionItemResource($transaction_item);
    }
    public function remove_tranaction_item($voucherId,$invoiceId)
    {
        $this->service->remove_tranaction_item($voucherId,$invoiceId);
        return \response()->json(['message' => 'Deleted Successfuly'],200);
    }

    public function remove_transaction_item_wallet($invoiceId)
    {
        $this->service->remove_transaction_item_wallet($invoiceId);
        return \response()->json(['message' => 'Deleted Successfuly'],200);
    }
    /**
     * Show the form for creating a new resource.
     */


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
