<?php

namespace Modules\AccountingFinance\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'product_name'=> $this->product_name,
            'unit_price'=> $this->unit_price,
            'quantity'=> $this->quantity,
            'user_id'=> $this->user_id,
            'currency_id'=> $this->currency_id,
            'duration'=> $this->duration,
            'remark'=> $this->remark,
            'discount_factor'=> $this->discount_factor ,
            'total'=> $this->total,
            'unit_price_base'=> $this->unit_price_base,
            'total_base'=> $this->total_base,
            'product' => $this->whenLoaded('product',function (){
                return [
                    'title' => $this->product->title['en'],
                    'product_code' => $this->product->product_code,
                ];
            }),
            'currency'=> $this->whenLoaded('currency',function (){
                return [
                    'abb' => $this->currency->abb,
                ];
            }),
        ];
    }
}
