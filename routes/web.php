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

    $values = [
        "R$100.000,00",
        "R$10.942,12",
        "R$1.912,12",
        "R$500,00",
        "R$25,00",
        "R$5,00"
    ];

    foreach ($values as $value) {
        dump(brl_to_bco($value));
    }

    exit;


    $url = "https://www.kabum.com.br/cgi-local/site/produtos/descricao_ofertas.cgi?codigo=79936";


    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    $html = curl_exec($ch);
    curl_close($ch);

    $crawler = new \Symfony\Component\DomCrawler\Crawler($html);

    $crawlerFindName  = $crawler->filterXPath('//title');
    $productName      = $crawlerFindName->text();

    $crawlerFindPrice = $crawler->filterXPath('//div[@class="preco_antigo-cm"]');
    $price            = brl_to_bco($crawlerFindPrice->text());

    $crawlerFindSale  = $crawler->filterXPath('//div[@class="preco_desconto-cm"]')->children();
    $sale             = brl_to_bco($crawlerFindSale->text());

    $crawlerFindImage = $crawler->filterXPath('//meta[@property="og:image"]');
    $imageContent     = $crawlerFindImage->extract('content');
    $image            = is_array($imageContent) ? $imageContent[0] : null;

    dd($image);

    exit;

});