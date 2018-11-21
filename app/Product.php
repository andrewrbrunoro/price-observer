<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'url', 'name', 'price', 'sale', 'first_price', 'first_sale', 'price', 'sale',
        'percent_off', 'total_off', 'times_read'
    ];
}
