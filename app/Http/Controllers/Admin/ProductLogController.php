<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class ProductLogController extends Controller
{
    public function index(Product $product, Request $request)
    {
        $product->load(['logs' => function ($q) {
            return $q->selectRaw('*, (SELECT SUM(inner_log.quantity) FROM product_logs AS inner_log WHERE inner_log.product_id = product_logs.product_id AND inner_log.id <= product_logs.id GROUP BY inner_log.product_id) AS balance')
                ->orderBy('id');
        }]);
        // dd($product->toArray());
        return view('admin.product-logs', [
            'product' => $product,
        ]);
    }

    public function adjust(Product $product, Request $request)
    {
        $request->validate([
            'quantity' => 'numeric',
            'action' => 'required|in:add,subtract',
        ]);

        $quantity = $request->action === 'subtract' ? (abs($request->quantity) * -1) : abs($request->quantity);

        $product->adjustQuantity($quantity);

        return redirect()->back();
    }
}
