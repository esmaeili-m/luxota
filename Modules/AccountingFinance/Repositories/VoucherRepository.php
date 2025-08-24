<?php

namespace Modules\AccountingFinance\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\AccountingFinance\App\Models\Voucher;

class VoucherRepository
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Voucher::with(['user','createdBy'])->paginate($perPage);
    }

    public function create($data)
    {
        return Voucher::create($data);
    }

    public function find(int $id)
    {
        return Voucher::findOrFail($id);
    }

    public function update(Voucher $voucher, array $data): bool
    {
        return $voucher->update($data);
    }
}
