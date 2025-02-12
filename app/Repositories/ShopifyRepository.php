<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ShopifyRepository
{
    public function fetchProducts() {
        $shop = Session::get('shop');
        $accessToken = Session::get('access_token');

        if (!$shop || !$accessToken) {
            abort(401, 'Unauthorized');
        }

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
        ])->get("https://{$shop}/admin/api/2023-01/products.json");


        return $response->json();
    }
}
