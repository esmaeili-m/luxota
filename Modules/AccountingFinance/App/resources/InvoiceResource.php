<?php

namespace Modules\AccountingFinance\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        $paid = ($this->total_base + $this->tax_amount_base) - $this->transactions_item->sum('amount');
        return [
            'id' => $this->id,
            'invoice_code' => $this->invoice_code,
            'user' => $this->whenLoaded('user',function (){
                return [
                    'name'=> $this->user->name,
                    'email'=> $this->user->email,
                    'avatar'=> $this->user->avatar,
                    'id'=>$this->user->id
                ];
            }),
            'currency' => $this->whenLoaded('currency',function (){
                return [
                    'abb'=> $this->currency->abb,
                    'id'=>$this->currency->id
                ];
            }),
            'transactions_item' => $this->whenLoaded('transactions_item',function (){
                return $this->transactions_item->map(function ($transaction_item) {
                    return [
                        'voucher_id' => $transaction_item->voucher_id,
                        'invoice_id' => $transaction_item->invoice_id,
                        'amount' => $transaction_item->amount,
                        'status' => $transaction_item->status,
                    ];
                });
            }),
            'date' => $this->date,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'total' => $this->total,
            'total_base' => $this->total_base,
            'tax_percent' => $this->tax_percent.'%',
            'tax_amount' => $this->tax_amount,
            'total_with_tax_amount' =>  $this->total + $this->tax_amount,
            'total_with_tax_amount_base' => $this->total_base + $this->tax_amount_base,
            'remark' => $this->remark,
            'paid' => floor($paid * 100) / 100,
            'invoice_items' => $this->whenLoaded('invoice_items',function ($items){
                return InvoiceItemResource::collection($items);
            })
        ];
    }
}
