<?php

namespace Modules\AccountingFinance\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\AccountingFinance\App\Http\Requests\CreateVoucherRequest;
use Modules\AccountingFinance\App\resources\VoucherResource;
use Modules\AccountingFinance\Services\VoucherService;

class AccountingFinanceController extends Controller
{
    public VoucherService $service;

    public function __construct(VoucherService $service)
    {
        $this->service= $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers=$this->service->all();
        return VoucherResource::collection($vouchers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateVoucherRequest $request)
    {
        $voucher = $this->service->create($request->validated());
        return new VoucherResource($voucher);
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
