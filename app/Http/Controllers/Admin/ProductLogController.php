<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class ProductLogController extends Controller
{
    public function __invoke(Product $product, Request $request)
    {
        return view('admin.product-logs', [
            'product' => $product,
        ]);
    }
}
