<?php

namespace App\Jobs;

use App\Mail\ProductAlertMail;
use App\Product;
use App\User;
use App\UserProductWatch;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProductAnalyticJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public $userProductWatch;

    public function __construct(
        UserProductWatch $userProductWatch
    )
    {
        $this->userProductWatch = $userProductWatch;
    }

    public function handle()
    {
        $watcher = $this->userProductWatch;
        
        $user = User::find($watcher->user_id);
        $product = Product::find($watcher->product_id);

        if ($product && $user) {
            #-> Metrica
            $percentOff = $watcher->discount;
            $firstPrice = $product->first_price;

            if ($product->first_price > 0) {
                $diffPrice = $firstPrice - (($percentOff / 100) * $firstPrice);

                if ($diffPrice <= coin_to_bco($product->price)) {

                    if ($watcher->email_at != "")
                        $hourDiff = $watcher->email_at->diffInHours(Carbon::now());
                    else
                        $hourDiff = 1;

                    if ($hourDiff > 0) {

                        \Mail::to($user->email)
                            ->send(new ProductAlertMail($product));

                        $watcher->update(['email_at' => Carbon::now()]);
                    }
                } else {
                    info('produto caro');
                }
                #-> Metrica
            }
        }
    }
}
