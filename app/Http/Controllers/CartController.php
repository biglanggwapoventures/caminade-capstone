<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $request->session()->get('_CART_', collect());

        return response()->json([
            'data' => $cart,
        ]);
    }

    public function store(Request $request)
    {
        $product = $request->session()->get('_CART_', collect());

        if (isset($product) && count($product) === 0) {
            $request->session()->push('_CART_', [
                'product_id' => $request->product_id,
                'product_quantity' => $request->product_quantity,
            ]);
        } else {
            $productid = collect($product)->where('product_id', $request->product_id)->first();

            if ($productid != null) {
                $this->edit($request);
            } else {
                $request->session()->push('_CART_', [
                    'product_id' => $request->product_id,
                    'product_quantity' => $request->product_quantity ? $request->product_quantity : 0,
                ]);
            }
        }

        return response()->json([
            'data' => $request->all(),
        ]);
    }

    public function edit(Request $request)
    {
        $product = $request->session()->get('_CART_', collect());

        if (isset($product) && count($product) > 0) {
            $new = collect($product)->transform(function ($value, $key) use ($request) {
                if ($value['product_id'] === $request->product_id) {
                    $value['product_quantity'] += $request->product_quantity ? $request->product_quantity : 0;
                }
                return $value;
            });

            $request->session()->put('_CART_', $new->toArray());
        }

        return response()->json([
            'data' => $product,
        ]);

    }

    public function destroy(Request $request)
    {
        $product = $request->session()->get('_CART_', collect());

        if (isset($product) && count($product) > 0) {
            $new = collect($product)->reject(function ($value, $key) use ($request) {
                return $value['product_id'] != $request->product_quantity ? $request->product_quantity : 0;
            });

            $request->session()->put('_CART_', $new->toArray());
        }
        return response()->json([
            'data' => $product,
        ]);
    }
}
