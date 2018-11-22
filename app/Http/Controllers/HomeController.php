<?php

namespace App\Http\Controllers;

use App\Product;
use App\Shop;

class HomeController extends Controller
{

    public function index()
    {
        $shops    = Shop::pluck('name', 'id');
        $products = Product::all();

        return view('home', compact('shops', 'products'));
    }



}
