<?php

namespace Modules\AccountingFinance\Services;

use Modules\AccountingFinance\Repositories\InvoiceRepository;
use Modules\Currency\Services\CurrencyService;
use Modules\Product\Repositories\ProductRepository;

class InvoiceService
{
    public InvoiceRepository $repo;
    public ProductRepository $productRepository;
    public CurrencyService $currencyService;

    public function __construct(InvoiceRepository $repo,ProductRepository $productRepository,CurrencyService $currencyService)
    {
        $this->repo = $repo;
        $this->currencyService = $currencyService;
        $this->productRepository = $productRepository;
    }
    public function add_item($data)
    {
        $product=$this->productRepository->find($data['product_id'],['prices']);
        $data['product_name']=$product->title['en'];
        $data['quantity']=1;
        $data['user_id']=auth()->user()->id;
        $data['unit_price']=$this->get_prices($product,$data['duration']);
        $prices=$this->get_prices($product,$data['duration']);
        $data= array_merge($data,$prices);
        return $this->repo->add_item($data);
    }

    public function remove_item($id)
    {
        return $this->repo->remove_item($id);

    }
    public function get_prices($product,$duration)
    {
        $user = auth()->user();
        if ($user){
            $zonePrice = $product->prices->where('zone_id', $user->zone_id)->first();
            $basePrice = $zonePrice?->price ?? 0;
            $currency = $user->city->country->currency;
            $rate = $currency->rate_to_usd ?? 1;
            $prices=[];
            $prices['unit_price_base']= (string) $basePrice;
            $prices['total_base']= (string) $basePrice * $duration;
            $prices['unit_price']= (string) $this->currencyService->convertFromUsd($basePrice , $currency);
            $prices['total']= (string) $this->currencyService->convertFromUsd($basePrice * $duration, $currency);
            return $prices;
        }
    }
}
