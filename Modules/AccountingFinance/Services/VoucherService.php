<?php

namespace Modules\AccountingFinance\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\AccountingFinance\App\Models\Voucher;
use Modules\AccountingFinance\Repositories\VoucherRepository;

class VoucherService
{
    public VoucherRepository $repo;

    public function __construct(VoucherRepository $repo)
    {
        $this->repo = $repo;
    }

    public function all()
    {
        $this->repo->getAll();
    }

    public function getPaginated(int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repo->paginate($perPage);
    }
    public function getById(int $id)
    {
        return $this->repo->find($id);
    }
    public function create($data)
    {
        $data['created_by']=auth()->user()->id;
        return $this->repo->create($data);
    }

    public function update(int $id, array $data): ?Voucher
    {
        $voucher = $this->repo->find($id);

        if (!$voucher) {
            return null;
        }

        $this->repo->update($voucher, $data);

        return $voucher->fresh();
    }
    public function toggle_status($voucher,$id)
    {
        $newStatus = !$voucher->status;
        $this->repo->update($voucher, ['status' => $newStatus]);
        return response()->json(['message' => 'Change Status successfully']);
    }
}
