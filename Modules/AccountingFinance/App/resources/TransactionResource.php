<?php

namespace Modules\AccountingFinance\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'invoice' => $this->whenLoaded('invoice',function (){
                return new InvoiceResource($this->invoice);
            }),
            'debit' => $this->debit,
            'credit' => $this->credit,
            'method' => $this->method,
            'voucher_id' => $this->voucher_id,
            'created_by' => $this->created_by,
            'balance' => $this->balance,
            'created_at' => $this->created_at->format('Y/m/d H:i:s'),
        ];
    }
}
