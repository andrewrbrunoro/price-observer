<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Product;
use App\Shop;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $shops    = Shop::pluck('name', 'id');
        $products = Product::all();

        return view('welcome', compact('shops', 'products'));
    }

    public function store(
        Product $product,
        ProductStoreRequest $productStoreRequest
    )
    {
        \DB::beginTransaction();
        try {

            $product->create($productStoreRequest->all());

            \DB::commit();
            return redirect()->route("product.index")
                ->with("success", "Produto cadastrado com sucesso, você pode visualizar as alterações dele na vitrine.");

        } catch (\Exception $e) {

            \DB::rollback();
            return redirect()->back()
                ->with("error", $e->getMessage());

        }
    }

}
