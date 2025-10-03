<?php

namespace Modules\AccountingFinance\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserVoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->voucher->title,
            'voucher_id' => $this->voucher->id,
            'amount' => $this->amount,
            'transactions' =>$this->voucher?->transactions,
            'expire' => now()->greaterThan($this->voucher->expires_at),
            'expires_at' => $this->voucher->expires_at,
        ];
    }
}
