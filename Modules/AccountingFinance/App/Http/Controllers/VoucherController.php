<?php

namespace Modules\AccountingFinance\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\AccountingFinance\App\Http\Requests\CreateVoucherRequest;
use Modules\AccountingFinance\App\Http\Requests\RedeemVoucherRequest;
use Modules\AccountingFinance\App\resources\UserVoucherResource;
use Modules\AccountingFinance\App\resources\VoucherResource;
use Modules\AccountingFinance\Services\VoucherService;

class VoucherController extends Controller
{
    public VoucherService $service;

    public function __construct(VoucherService $service)
    {
        $this->service= $service;
    }
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $cities = $this->service->getPaginated();
        return VoucherResource::collection($cities);
    }

    public function store(CreateVoucherRequest $request): VoucherResource
    {
        $voucher = $this->service->create($request->validated());
        return new VoucherResource($voucher);
    }

    public function show($id): VoucherResource
    {
        $voucher = $this->service->getById($id);

        if (!$voucher) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new VoucherResource($voucher);
    }

    public function update(CreateVoucherRequest $request, $id): VoucherResource
    {
        $voucher = $this->service->update($id, $request->validated());

        if (!$voucher) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new VoucherResource($voucher);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function toggle_status($id): JsonResponse
    {
        $voucher = $this->service->getById($id);

        if (!$voucher) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $this->service->toggle_status($voucher, $id);

        return \response()->json('Voucher Updated Status Successfuly');
    }

    public function get_vouchers_user()
    {
        $vouchers=$this->service->get_vouchers_user();

        return UserVoucherResource::collection($vouchers);
    }

    public function redeem_voucher(RedeemVoucherRequest $request)
    {
        $voucher=$this->service->redeem_voucher($request->validated());
        return new VoucherResource($voucher);
    }

}
