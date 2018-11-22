<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'job', 'url', 'name', 'price', 'sale', 'first_price',
        'first_sale', 'price', 'sale', 'times_read'
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

    public function PriceHistories()
    {
        return $this->hasMany(PriceHistory::class, 'product_id')
            ->orderByDesc('created_at');
    }

    public function UserProductWatches()
    {
        return $this->hasMany(UserProductWatch::class, 'product_id');
    }

}
