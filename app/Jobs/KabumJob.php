<?php

namespace App\Jobs;

use App\Helpers\Curl;
use App\PriceHistory;
use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\DomCrawler\Crawler;

class KabumJob implements ShouldQueue
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

        $html = Curl::request($product->url);

        $crawler = new Crawler($html);

        $crawlerFindName  = $crawler->filterXPath('//title');
        $productName      = $crawlerFindName->text();

        $crawlerFindPrice = $crawler->filterXPath('//div[@class="preco_antigo-cm"]');
        if (is_null($crawlerFindPrice->getNode(0))) {
            $crawlerFindPrice = $crawler->filterXPath('//div[@class="preco_normal"]');
        }

        $price            = brl_to_bco($crawlerFindPrice->text());

        $crawlerFindSale  = $crawler->filterXPath('//div[@class="preco_desconto-cm"]');
        if (is_null($crawlerFindSale->getNode(0))) {
            $crawlerFindSale  = $crawler->filterXPath('//div[@class="preco_desconto"]');
        }
        $crawlerFindSale  = $crawlerFindSale->children();

        $sale             = brl_to_bco($crawlerFindSale->text());

        $crawlerFindImage = $crawler->filterXPath('//meta[@property="og:image"]');
        $image            = $crawlerFindImage->attr('content');


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
    }
}
