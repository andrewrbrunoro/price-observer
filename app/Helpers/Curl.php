<?php
namespace App\Helpers;

class Curl
{

    public static function request($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, storage_path('/tmp/cookies.txt'));
        curl_setopt($ch, CURLOPT_COOKIEFILE, storage_path('/tmp/cookies.txt'));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_STDERR,  fopen('php://stdout', 'w'));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $html = curl_exec($ch);
        curl_close($ch);

        return $html;
    }

}
