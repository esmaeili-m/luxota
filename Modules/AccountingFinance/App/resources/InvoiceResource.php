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
        return [
            'id' => $this->id,
            'invoice_code' => $this->invoice_code,
            'user' => $this->whenLoaded('user',function (){
                return [
                    'name'=> $this->user->name,
                    'id'=>$this->user->id
                ];
            }),
            'currency' => $this->whenLoaded('currency',function (){
                return [
                    'abb'=> $this->currency->abb,
                    'id'=>$this->currency->id
                ];
            }),
            'date' => $this->date,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'total' => $this->total,
            'total_base' => $this->total_base,
            'remark' => $this->remark,
            'invoice_items' => $this->whenLoaded('invoice_items',function ($items){
                return InvoiceItemResource::collection($items);
            })
        ];
    }
}
