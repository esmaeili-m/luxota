<?php

namespace Modules\AccountingFinance\App\resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'amount' => $this->amount,
            'user_id' => $this->user_id,
            'remark' => $this->remark,
            'expires_at' => $this->expires_at,
            'created_by' => $this->created_by,
            'code' => $this->code,
            'redeemed_at' => Carbon::parse($this->redeemed_at)->format('Y-m-d'),
            'created_at' => $this->created_at,
            'status' => $this->status,
            'user' => $this->whenLoaded('user',function ($user){
                return [
                    'name' => $user->name,
                ];
            }),
            'createdBy' => $this->whenLoaded('createdBy',function ($createdBy){
                return [
                    'name' => $createdBy?->name,
                    'avatar' => $createdBy?->avatar,
                ];
            })
        ];
    }
}
