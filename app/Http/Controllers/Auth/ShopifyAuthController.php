<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ShopifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShopifyAuthController extends Controller
{
    protected $shopifyService;

    public function __construct(ShopifyService $shopifyService)
    {
        $this->shopifyService = $shopifyService;
    }

    public function redirectToShopify(Request $request)
    {
        $shop = $request->query('shop_store') ?? config('app.shopify.shop');
        return redirect($this->shopifyService->getAuthUrl($shop));
    }

    public function handleCallback(Request $request)
    {
        return $this->shopifyService->processOAuthCallback($request);
    }
}
