<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceHistory extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'product_id', 'price', 'sale'
    ];

}
