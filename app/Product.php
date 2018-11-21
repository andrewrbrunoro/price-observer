<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'job', 'url', 'name', 'price', 'sale', 'first_price', 'first_sale', 'price', 'sale',
        'percent_off', 'total_off', 'times_read'
    ];

    public function getPriceAttribute($value)
    {
        return bco_to_coin($value);
    }

    public function getSaleAttribute($value)
    {
        return bco_to_coin($value);
    }

    public function setJobAttribute(
        $value
    )
    {
        if (filter_var($value, FILTER_VALIDATE_INT))
            $job = Shop::nameJob((int)$value);
        else
            $job = $value;

        $this->attributes['job'] = $job;
    }

    public function setPercentOffAttribute(
        $value
    )
    {
        if (is_null($value))
            $coin = "0.00";
        else {
            $coin = coin_to_bco($value);
        }

        $this->attributes['percent_off'] = $coin;
    }

    public function setTotalOffAttribute(
        $value
    )
    {
        if (is_null($value))
            $coin = "0.00";
        else {
            $coin = coin_to_bco($value);
        }

        $this->attributes['total_off'] = $coin;
    }

}
