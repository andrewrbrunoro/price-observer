<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

//Route::group([
//    'prefix' => 'servico',
//    'namespace' => 'Auth'
//], function ($serviceRoute) {
//    $serviceRoute->get('acessar', 'LoginController@login')
//        ->name('login');
//});

Auth::routes();

Route::group([
    'middleware' => 'auth'
], function($auth) {
    $auth->get('/', 'HomeController@index')
        ->name('home');

    $auth->post('produto/criar', 'ProductController@store')
        ->name('product.store');

    $auth->get('produto/{id}/visualizar', 'ProductController@show')
        ->name('product.show');
});



Route::get('ajax/products', 'ProductController@ajaxProducts');

Route::get('send-email', function () {
    \Mail::to('andrewrbrunoro@gmail.com')
        ->send(new \App\Mail\ProductAlertMail(\App\Product::find(1)));
});

Route::get('teste', function () {


    $url = "https://www.magazineluiza.com.br/cooktop-5-bocas-philco-cook-chef-5-tc-a-gas-natural-e-glp-tripla-chama/p/216590800/ed/cook/";


    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    $html = curl_exec($ch);
    curl_close($ch);

    $crawler = new \Symfony\Component\DomCrawler\Crawler($html);

    $product = $crawler->filterXPath('//div[@class="header-product js-header-product"]')->extract('data-product');
    $product = json_decode($product[0]);

    dd($product);

    $productName      = $product->fullTitle;
    $price            = coin_to_bco($product->listPrice);
    $sale             = coin_to_bco($product->priceTemplate);
    $image            = $product->imageUrl;

    exit;

});