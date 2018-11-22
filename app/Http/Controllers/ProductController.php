<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\PriceHistory;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function ajaxProducts()
    {
        return Product::all();
    }

    public function show(
        int $id
    )
    {
        $product = Product::with('PriceHistories')->find($id);
        if (!$product)
            return redirect()->route('home')
                ->with('error', 'Registro não encontrado');

        $chartQuery = PriceHistory::select(
            \DB::raw('max(price) as maxPrice'),
            \DB::raw('min(price) as minPrice'),
            \DB::raw('any_value(DATE_FORMAT(created_at, "%Y")) as year'),
            \DB::raw('DATE_FORMAT(created_at, "%m") as month')
        )->groupBy('month')
            ->where('product_id', $id)
            ->get();

        $chartData = [];
        $min = [];
        $max = [];
        $currentYear = Carbon::now()->year;

        if (!$chartQuery->count()) {
            for ($i = 1; $i <= 12; $i++) {
                $counter = $i < 10 ? "0".$i : $i;
                $min[$currentYear][$counter] = 0;
                $max[$currentYear][$counter] = 0;
            }
        } else {
            foreach ($chartQuery as $item) {
                $chartData[$item->year]['min'][$item->month] = $item->minPrice;
                $chartData[$item->year]['max'][$item->month] = $item->maxPrice;
            }

            foreach ($chartData as $year => $chartDatum) {
                for ($i = 1; $i <= 12; $i++) {
                    $counter = $i < 10 ? "0".$i : $i;
                    $min[$year][$counter] = !isset($chartDatum['min'][$counter]) ? 0 : $chartDatum['min'][$counter];
                    $max[$year][$counter] = !isset($chartDatum['max'][$counter]) ? 0 : $chartDatum['max'][$counter];
                }
            }
        }

        $min = json_encode(array_values($min[$currentYear]));
        $max = json_encode(array_values($max[$currentYear]));

        return view('product.show', compact('product', 'min', 'max'));
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
            return redirect()->route("home")
                ->with("success", "Produto cadastrado com sucesso, você pode visualizar as alterações dele na vitrine.");

        } catch (\Exception $e) {

            \DB::rollback();
            return redirect()->back()
                ->with("error", $e->getMessage());

        }
    }

}
