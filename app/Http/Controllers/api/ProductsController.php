<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(){

        $products = Categorie::with(['products' => function ($q) {
            $q->withCount('reviews'); // ekhane review_count ashbe
        }])
            ->select('name','id')
            ->orderBy('sort')
            ->paginate(50);
        return response()->json([
            'status' => true,
            'data' => $products->items(),
            'total' => $products->total(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
        ]);
    }

    public function show($slug)
    {
        try {
            $product = Product::with('items')->where('slug', $slug)->first();

            if ($product) {
                return response()->json([
                    'status' => true,
                    'data' => $product,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found',
                ]);
            }

        }catch (\Exception $exception){
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
