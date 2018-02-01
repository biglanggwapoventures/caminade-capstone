<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductCategory;
use Illuminate\Http\Request;

class ProductShowcaseController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'category' => 'array',
            'category.*' => 'exists:product_categories,id',
        ]);

        $query = Product::forShowcase();

        $query->when(!empty($request->category), function ($q) use ($request) {
            $q->whereIn('product_category_id', $request->category);
        });

        $query->when($request->has('name'), function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->name}%");
        });

        return view('product-showcase', [
            'categories' => ProductCategory::pluck('description', 'id'),
            'data' => $query->get(),
        ]);
    }
}
