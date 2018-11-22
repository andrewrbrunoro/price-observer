<?php

namespace App\Jobs;

use App\Mail\ProductAlertMail;
use App\PriceHistory;
use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\DomCrawler\Crawler;

class PichauJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public $product;

    public function __construct(
        Product $product
    )
    {
        $this->product = $product;
    }

    public function handle()
    {
        $product = $this->product;

        $url = $product->url;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $html = curl_exec($ch);
        curl_close($ch);

        $crawler = new Crawler($html);

        $crawlerFindName  = $crawler->filterXPath('//div[@class="product title"]')->children();
        $productName      = $crawlerFindName->text();

        $crawlerFindPrice = $crawler->filterXPath('//span[@class="price"]');
        $price            = brl_to_bco($crawlerFindPrice->text());

        $crawlerFindSale  = $crawler->filterXPath('//span[@class="price-boleto"]');
        $sale             = brl_to_bco($crawlerFindSale->text());

        $crawlerFindImage = $crawler->filterXPath('//meta[@property="og:image"]');
        $imageContent     = $crawlerFindImage->extract('content');
        $image            = is_array($imageContent) ? $imageContent[0] : null;


        $lastHistoryPrice = PriceHistory::where('product_id', '=', $product->id)
            ->orderByDesc('created_at')
            ->first();

        if (!$lastHistoryPrice || $lastHistoryPrice->price != $price) {
            PriceHistory::create([
                'product_id' => $product->id,
                'price' => $price,
                'sale' => $sale
            ]);
        }

        $product->name      = $productName;
        $product->price     = $price;
        $product->sale      = $sale;
        $product->image     = $image;

        if ($product->times_read === 0) {
            $product->first_price = $price;
            $product->first_sale  = $sale;
        }

        $product->times_read = $product->times_read + 1;
        $product->save();

//        $this->analytics();
    }

    private function analytics()
    {
        $product = $this->product;

        #-> Metrica
        $percentOff = $product->percent_off;
        $firstPrice = $product->first_price;

        $diffPrice  = $firstPrice - (($percentOff / 100) * $firstPrice);
        if ($diffPrice <= $product->price) {
            \Mail::to('andrewrbrunoro@gmail.com')
                ->send(new ProductAlertMail($this->product));
        } else {
            // produto caro
        }
        #-> Metrica
    }
}
