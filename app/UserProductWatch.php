<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProductWatch extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'user_id', 'product_id', 'discount'
    ];

    public function setDiscountAttribute(
        $value
    )
    {
        if (is_null($value))
            $coin = "0.00";
        else {
            $coin = coin_to_bco($value);
        }

        $this->attributes['discount'] = $coin;
    }

}
