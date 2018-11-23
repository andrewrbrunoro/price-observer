<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\PriceHistory;
use App\Product;
use App\Shop;
use App\User;
use App\UserProductWatch;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function ajaxProducts(
        Request $request
    )
    {
        if ($request->filled('usuario')) {
            $user = User::find($request->get('usuario'));
            if (!$user)
                return redirect()->route( 'home')->with('error', 'Usuário não encontrado.');

            $user_id = $user->id;
        } else {
            $user_id = auth()->user()->id;
        }

        $userWatches = UserProductWatch::where('user_id', $user_id)
            ->pluck('product_id', 'product_id');
        return Product::whereIn('id', $userWatches)->get();
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

            $productData = Product::where('url', '=', $productStoreRequest->get('url'))
                ->first();

            $jobData     = Shop::getShop($productStoreRequest->get('job'));
            if (!$jobData)
                return redirect()->route('home')->with('error', 'Loja não encontrada');

            $parseUrl = parse_url($productStoreRequest->get('url'));
            $hostUrl  = $parseUrl['host'];
            $wHostUrl = str_replace('www.', '', $hostUrl);

            if ($hostUrl != $jobData->domain && $wHostUrl != $jobData->domain)
                return redirect()->route('home')->with('error', 'O link digitado não coincide com a loja selecionada.')
                    ->withInput($productStoreRequest->all());

            if (!$productData) {
                $productData = $product->create($productStoreRequest->all());
            }

            $userAlreadyWatch = UserProductWatch::where([
                'user_id'    => auth()->user()->id,
                'product_id' => $productData->id,
                'discount'   => coin_to_bco($productStoreRequest->get('percent_off'))
            ])->first();

            if (!$userAlreadyWatch) {
                $productData->UserProductWatches()->saveMany([
                    new UserProductWatch([
                        'user_id' => auth()->user()->id,
                        'discount' => coin_to_bco($productStoreRequest->get('percent_off'))
                    ])
                ]);
            }

            \DB::commit();

            if (!strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
                \Artisan::call('products:read');

            return redirect()->route("home")
                ->with("success", "Produto cadastrado com sucesso, você pode visualizar as alterações dele na vitrine.");

        } catch (\Exception $e) {

            \DB::rollback();

            dd($e);

            return redirect()->back()
                ->with("error", $e->getMessage());

        }
    }

}
