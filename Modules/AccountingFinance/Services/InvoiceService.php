<?php

namespace Modules\AccountingFinance\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

    public function get_invoice_items()
    {
        return $this->repo->get_items();
    }
    public function add_item($data)
    {
        $product=$this->productRepository->find($data['product_id'],['prices']);
        $discount_factor=1;
        if ($data['duration'] == 3){
            $discount_factor=0.8;
        }elseif ($data['duration'] == 6){
            $discount_factor=0.55;
        }
        $data['product_name']=$product->title['en'];
        $data['quantity']=1;
        $data['discount_factor']=$discount_factor;
        $data['user_id']=auth()->user()->id;
        $data['currency_id']=\auth()->user()->city->country->currency->id;
        $prices=$this->get_prices($product,$data['duration'],$discount_factor);
        $data= array_merge($data,$prices);
        return $this->repo->add_item($data);
    }

    public function remove_item($id)
    {
        return $this->repo->remove_item($id);

    }
    public function getCartCount()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['count' => 0]);
        }
        $count = $this->repo->InvoiceItemCount($user);

        return response()->json(['count' => $count]);
    }

    public function createInvoice($data)
    {
        $user = auth()->user();
        $invoice = [];
        $invoice['invoice_code'] = $this->generate_code();
        $invoice['user_id'] = $user->id;
        $invoice['created_by'] = $user->id;
        $invoice['date'] = now();
        $invoice['status'] = 0;
        $invoice['currency_id'] = $user->city->country->currency->id;
        $invoice['exchange_rate'] = $user->city->country->currency->rate_to_usd;
        $invoice['total'] = 0;
        $invoice['total_base'] = 0;
        $invoice = $this->repo->createInvoice($invoice);
        $prices = $this->get_prices_total($data, $invoice->id);
        $this->repo->updateInvoice($invoice->id, [
            'total' => $prices['total'],
            'total_base' => $prices['total_base']
        ]);
        return $invoice;
    }

    public function get_invoice($invoice_code)
    {
        return $this->repo->get_invoice($invoice_code);
    }
    public function get_prices_total($data, $invoice_id)
    {
        $invoiceItems = $this->repo->get_items();
        $total = 0;
        $total_base = 0;

        foreach ($invoiceItems ?? [] as $item) {
            $quantity = $data['quantits'][$item->id] ?? 1;
            $t = $item->total * $quantity;
            $tb = $item->total_base * $quantity;

            $this->repo->update_invoiceItem($item, [
                'remark' => $data['remarks'][$item->id] ?? '',
                'quantity' => $quantity,
                'total'=> $t,
                'total_base'=> $tb,
                'status' => 1,
                'invoice_id' => $invoice_id
            ]);

            $total += $t;
            $total_base += $tb;
        }

        return [
            'total' => $total,
            'total_base' => $total_base
        ];
    }

    public function get_invoices_user()
    {
        return $this->repo->get_invoices_user();
    }
    public function get_prices($product,$duration,$discount_factor=1)
    {
        $user = auth()->user();
        if ($user){
            $zonePrice = $product->prices->where('zone_id', $user->zone_id)->first();
            $basePrice = $zonePrice?->price ?? 0;
            $currency = $user->city->country->currency;
            $rate = $currency->rate_to_usd ?? 1;
            $prices=[];
            $prices['unit_price_base']= (string) $basePrice;
            $prices['total_base']= (string) ($basePrice * $duration * $discount_factor);
            $prices['unit_price']= (string) $this->currencyService->convertFromUsd($basePrice , $currency);
            $prices['total']= (string) $this->currencyService->convertFromUsd($basePrice * $duration * $discount_factor, $currency);
            return $prices;
        }
    }
    public function generate_code()
    {
        $invoice_code=$this->repo->get_max_invoice_code();
        return  $invoice_code ? $invoice_code  + 1 : 1000;
    }

}
