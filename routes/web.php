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

Route::get('/', 'HomeController@index')
    ->name('home');

Route::post('produto/criar', 'ProductController@store')
    ->name('product.store');

Route::get('produto/{id}/visualizar', 'ProductController@show')
    ->name('product.show');


Route::get('send-email', function() {
    \Mail::to('andrewrbrunoro@gmail.com')
        ->send(new \App\Mail\ProductAlertMail(\App\Product::find(1)));
});

Route::get('teste', function() {

    $url = "https://www.pichau.com.br/computador-pichau-gamer-i5-8400-geforce-gtx-1070-ti-8gb-gigabyte-windforce-8gb-ddr4-hd-1tb-600w-elysium";

    $ch  = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    $html = curl_exec($ch);
    curl_close($ch);

    $crawler = new \Symfony\Component\DomCrawler\Crawler($html);

    $imagesCrawler = $crawler->filterXPath('//meta[@property="og:image"]');

    dd($imagesCrawler->extract('content'));

    $images  = $crawler->filterXPath('//img[@class="fotorama__img"]')->each(function(\Symfony\Component\DomCrawler\Crawler $node, $i) {
        dump($node->extract('src'));
    });

    dd("here");

});