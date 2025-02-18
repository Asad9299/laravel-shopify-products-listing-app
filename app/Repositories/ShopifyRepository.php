<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Shop;
use Illuminate\Support\Facades\Log;
 
class ShopifyRepository
{
    public function fetchProducts($shop)
    {
        // $shop        = "test-2025-store.myshopify.com";
        $accessToken = Shop::getAccessToken( $shop );
        
        if (!$shop || !$accessToken) {
            abort(401, 'Unauthorized');
        }

        $query = <<<GQL
        {
            products(first: 10) {
                edges {
                    node {
                        id
                        title
                        handle
                        description
                        images(first: 1) {
                            edges {
                                node {
                                    src
                                }
                            }
                        }
                        variants(first: 1) {
                            edges {
                                node {
                                    price
                                    sku
                                }
                            }
                        }
                    }
                }
            }
        }
        GQL;

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
            'Content-Type' => 'application/json'
        ])->post("https://{$shop}/admin/api/2023-01/graphql.json", [
            'query' => $query
        ]);

        return $response->json();
    }
}
