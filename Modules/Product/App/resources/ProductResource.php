<?php

namespace Modules\Product\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\AccountingFinance\App\Models\InvoiceItem;
use Modules\AccountingFinance\App\resources\InvoiceItemResource;
use Modules\Currency\Services\CurrencyService;

class ProductResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {

        $user = $request->user();
        if ($user){
            $zonePrice = $this->prices->where('zone_id', $user->zone_id)->first();
            $basePrice = $zonePrice?->price ?? 0;
            $currencyService = app(CurrencyService::class);
            $currency = $user->city->country->currency;
            $rate = $currency->rate_to_usd ?? 1;
            $plans = [
                'one_month' => [
                    'total'     => $basePrice,
                    'per_month' => $basePrice,
                ],
                'three_months' => [
                    'total'     => $basePrice * 3 * 0.8,
                    'per_month' => ($basePrice * 3 * 0.8) / 3,
                ],
                'six_months' => [
                    'total'     => $basePrice * 6 * 0.55,
                    'per_month' => ($basePrice * 6 * 0.55) / 6,
                ],
            ];
            foreach ($plans  as $key => $plan) {
                $plans[$key]['total'] = (string) $currencyService->convertFromUsd($plan['total'], $currency);
                $plans[$key]['per_month'] = (string) $currencyService->convertFromUsd($plan['per_month'], $currency);
            }
        }
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'product_code' => $this->product_code,
            'last_version_update_date' => $this->last_version_update_date?->format('Y-m-d'),
            'version'     => $this->version,
            'author' => $this->whenLoaded('author', function () {
                return [
                    'id'   => $this->author->id,
                    'name' => $this->author->name,
                    'products_count' => $this->author->products->count(),
                ];
            }),
            'image'       => $this->image,
            'comments_count' => $this->whenLoaded('comments', function () {
                return $this->comments->count();
            }),
            'comments' => $this->whenLoaded('comments', function () {
                return $this->comments->map(function ($child){
                    return new CommentResource($child);
                });
            }),
            'score' => $this->whenLoaded('comments', function () {
                return $this->comments->avg('score') ?? 0;
            }),
            'active_item' => $this->whenLoaded('active_item',function (){
                return new InvoiceItemResource($this->active_item);
            }),
            'video_script' => $this->video_script,
            'slug'        => $this->slug,
            'order'       => $this->order,
            'show_price'  => $this->show_price,
            'payment_type' => $this->payment_type,
            'status'      => $this->status,
            'status_label'=> $this->status ? 'active' : 'inactive',
            'category_id' => $this->category_id,
            'category'    => new \Modules\Category\App\resources\CategoryResource($this->whenLoaded('category')),
            'plans' => $plans ?? [],
            'currency' => $currency->abb ?? 'USD',
            'created_at'  => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'  => $this->updated_at?->format('Y-m-d H:i:s'),

        ];
    }
}
