<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [];

        $urls[] = [
            'loc' => url('/'),
            'lastmod' => Carbon::now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '1.0'
        ];

        $urls[] = [
            'loc' => url('/about'),
            'lastmod' => Carbon::now()->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.8'
        ];

        $products = Product::where('name', '!=', 'Wallet')->get();
        foreach ($products as $product) {
            $urls[] = [
                'loc' => url('/product/' . $product->slug),
                'lastmod' => $product->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '1.0'
            ];
        }


        $content = view('sitemap', compact('urls'));

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
