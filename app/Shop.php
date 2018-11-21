<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name', 'job'
    ];

    public static function nameJob(
        int $shop_id
    )
    {
        $jobData = self::find($shop_id);
        return $jobData->job;
    }

}
