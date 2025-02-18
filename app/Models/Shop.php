<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
        'shopify_domain',
        'access_token'
    ];

    public static function getAccessToken ( $shop ) {
        return self::where('shopify_domain', $shop)->pluck('access_token')->first();
    }
}
