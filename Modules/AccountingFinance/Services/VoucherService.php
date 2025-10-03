<?php

namespace Modules\AccountingFinance\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\AccountingFinance\App\Models\Voucher;
use Modules\AccountingFinance\Repositories\TransactionRepository;
use Modules\AccountingFinance\Repositories\VoucherRepository;

class VoucherService
{
    public VoucherRepository $repo;
    public TransactionRepository $transaction_repo;

    public function __construct(VoucherRepository $repo,TransactionRepository $transaction_repo)
    {
        $this->repo = $repo;
        $this->transaction_repo=$transaction_repo;
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

    public function get_vouchers_user()
    {
        return $this->repo->get_vouchers_user();
    }

    public function redeem_voucher($data)
    {
        $voucher=$this->repo->find_voucher_with_code($data['code']);
        $user_voucher=[];
        $user_voucher['voucher_id']=$voucher->id;
        $user_voucher['user_id']= auth()->user()->id;
        $user_voucher['amount']=$voucher->amount;
        $tranaction=[];
        $tranaction['user_id']=$user_voucher['user_id'];
        $tranaction['credit']=$voucher->amount;
        $tranaction['method']=2;
        $tranaction['created_by']=$user_voucher['user_id'];
        $tranaction['voucher_id']=$user_voucher['voucher_id'];
        $this->transaction_repo->create_transaction($tranaction);
        return $this->repo->redeem_voucher($user_voucher);
    }
}
