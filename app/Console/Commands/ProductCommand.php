<?php

namespace App\Console\Commands;

use App\Jobs\PichauJob;
use App\Product;
use Illuminate\Console\Command;

class ProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $products = Product::orderBy('created_at', 'desc')->get();

        foreach ($products as $product) {
            dispatch(
                new $product->job($product)
            );
        }
    }
}
