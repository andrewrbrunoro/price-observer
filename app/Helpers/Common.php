<?php
if (!function_exists('brl_to_bco')) {
    function brl_to_bco($value)
    {

        $value = str_replace(' ', '', $value);
        $value = explode('R$', $value);

        if (!isset($value[1]))
            return "0.00";

        $value = preg_replace("/[^0-9.,]/","", $value[1]);

        $valueBco = str_replace("R$", "", $value);

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