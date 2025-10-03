<?php

namespace Modules\AccountingFinance\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\AccountingFinance\App\Http\Requests\CreateTransactionItemRequest;
use Modules\AccountingFinance\App\resources\TransactionResource;
use Modules\AccountingFinance\Services\TransactionService;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public TransactionService $service;

    public function __construct(TransactionService $service)
    {
        $this->service=$service;
    }
    public function get_transactions_user_wallet()
    {
        $trasncations=$this->service->get_transactions_user_wallet();
        return TransactionResource::collection($trasncations);
    }

    public function get_transactions_user($voucher_id)
    {
        $trasncations=$this->service->get_transactions_user($voucher_id);
        return TransactionResource::collection($trasncations);

    }

    public function create_tranaction_item(CreateTransactionItemRequest $request)
    {

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
