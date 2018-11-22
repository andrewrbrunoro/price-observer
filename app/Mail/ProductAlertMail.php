<?php

namespace App\Mail;

use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductAlertMail extends Mailable
{
    use Queueable,
        SerializesModels;

    public $subject = "Preço baixou em um dos produtos - PriceObserver";

    public $product;

    public function __construct(
        Product $product
    )
    {
        $this->product = $product;
    }

    public function build()
    {
        return $this->view('emails.products.alert');
    }
}
