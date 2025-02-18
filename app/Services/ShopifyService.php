<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Shop;

class ShopifyService
{
    private $apiKey;
    private $apiSecret;
    private $redirectUri;
    private $scope;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->apiKey      = config('app.shopify.api_key');
        $this->apiSecret   = config('app.shopify.api_secret');
        $this->redirectUri = config('app.shopify.redirect_uri');
        $this->scope       = config('app.shopify.scope');
    }

    public function getAuthUrl( $shop )
    {
        return "https://{$shop}/admin/oauth/authorize?" . http_build_query([
            'client_id'    => $this->apiKey,
            'scope'        => $this->scope,
            'redirect_uri' => $this->redirectUri,
            'state'        => csrf_token(),
        ]);
    }

    public function processOAuthCallback( $request )
    {
        $shop = $request->query('shop');
        $code = $request->query('code');

        $response = Http::post("https://{$shop}/admin/oauth/access_token", [
            'client_id'     => $this->apiKey,
            'client_secret' => $this->apiSecret,
            'code'          => $code,
        ]);

        $data = $response->json();
        if (!isset($data['access_token'])) {
            abort(403, 'Access token not received');
        }

        Session::put('shop', $shop);
        // Store or update the shop in the database
        Shop::updateOrCreate(
            ['shopify_domain' => $shop],
            ['access_token' => $data['access_token']]
        );

        return redirect('/shopify/products');
    }
}
