<?php

namespace App\Http\Controllers;

use App\Product;

class ProductShowcaseController extends Controller
{
    public function __invoke()
    {
        return view('product-showcase', [
            'data' => Product::whereProductStatus('active')->with('category')->orderBy('name')->get(),
        ]);
    }
}
