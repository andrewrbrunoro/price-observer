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


    $url = "https://www.americanas.com.br/produto/132380287/smartphone-samsung-galaxy-j5-pro-dual-chip-android-7-0-tela-5-2-octa-core-1-6-ghz-32gb-4g-camera-13mp-preto?DCSext.recom=RR_home_page.rr1-PersonalizedClickCP&nm_origem=rec_home_page.rr1-PersonalizedClickCP&nm_ranking_rec=1&pfm_carac=A%20gente%20acha%20que%20voc%C3%AA%20vai%20curtir%20esses&pfm_index=0&pfm_page=home&pfm_pos=home_page.rr1&pfm_type=vit_recommendation";


    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    $html = curl_exec($ch);
    curl_close($ch);

    $crawler = new \Symfony\Component\DomCrawler\Crawler($html);

    $crawlerFindName  = $crawler->filterXPath('//title');
    $productName      = $crawlerFindName->text();

    $crawlerFindPrice = $crawler->filterXPath('//p[@class="sales-price"]');
    $price            = brl_to_bco($crawlerFindPrice->text());

    dd($price);

    $crawlerFindSale  = $crawler->filterXPath('//span[@class="price-boleto"]');
    $sale             = brl_to_bco($crawlerFindSale->text());

    $crawlerFindImage = $crawler->filterXPath('//meta[@property="og:image"]');
    $imageContent     = $crawlerFindImage->extract('content');
    $image            = is_array($imageContent) ? $imageContent[0] : null;

    exit;

});