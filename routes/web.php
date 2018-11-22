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

    $hourDiff = \Carbon\Carbon::now()->subHour(1)->diffInHours(\Carbon\Carbon::now());

    dd($hourDiff);

    $url = "https://www.pichau.com.br/computador-pichau-gamer-i5-8400-geforce-gtx-1070-ti-8gb-gigabyte-windforce-8gb-ddr4-hd-1tb-600w-elysium";

    $tags = get_meta_tags("https://www.pichau.com.br/computador-pichau-gamer-i5-8400-geforce-gtx-1070-ti-8gb-gigabyte-windforce-8gb-ddr4-hd-1tb-600w-elysium");

    dd($tags);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_FILETIME, true);
    curl_setopt($curl, CURLOPT_NOBODY, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    $header = curl_exec($curl);
    $info   = curl_getinfo($curl);
    curl_close($curl);

    dd($header, $info);

    exit;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    $html = curl_exec($ch);
    curl_close($ch);

    $crawler = new \Symfony\Component\DomCrawler\Crawler($html);

    $imagesCrawler = $crawler->filterXPath('//meta[@property="og:image"]');

    dd($imagesCrawler->extract('content'));

    $images = $crawler->filterXPath('//img[@class="fotorama__img"]')->each(function (\Symfony\Component\DomCrawler\Crawler $node, $i) {
        dump($node->extract('src'));
    });

    dd("here");

});