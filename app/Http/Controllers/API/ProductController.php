<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\ShopifyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    protected $shopifyRepository;
    public function __construct(ShopifyRepository $shopifyRepository)
    {
        $this->shopifyRepository = $shopifyRepository;

    }

    public function getProducts(): JsonResponse {
        return response()->json($this->shopifyRepository->fetchProducts());
    }
}
