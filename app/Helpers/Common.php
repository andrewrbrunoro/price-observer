<?php
if (!function_exists('brl_to_bco')) {
    function brl_to_bco($value)
    {
        $value = str_replace(' ', '', $value);

        preg_match('/R\$\d+(?:\.\d+?)?(?:\,\d+)?/', $value, $matches);
        $firstFound = array_first($matches);

        if (!$firstFound)
            return "0.00";

        $valueBco = str_replace("R$", "", $firstFound);

        return coin_to_bco($valueBco);
    }
}


if (!function_exists('bco_to_coin')) {
    function bco_to_coin($value)
    {
        return number_format($value, 2, ',', '.');
    }
}

if (!function_exists('coin_to_bco')) {
    function coin_to_bco($get_valor)
    {
        if (strstr($get_valor, ',')) {
            $source = array(
                '.',
                ','
            );
            $replace = array(
                '',
                '.'
            );
            $valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
        } else
            $valor = $get_valor;

        return $valor; //retorna o valor formatado para gravar no banco
    }
}