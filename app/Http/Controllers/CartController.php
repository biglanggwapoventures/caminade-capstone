<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Order;
use App\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {        
        $product_session = $request->session()->get('_CART_', collect());
        
        $product_ids = collect($product_session);

        $products = Product::with(['category', 'logs'])
                    ->whereIn('id', $product_ids->pluck('product_id'))
                    ->get();

        $cart_list = $product_ids->map(function ($product_id) use ($products) {
            return $products->flatMap(function ($product) use ($product_id) {
                if($product->id == $product_id['product_id']) {
                    return [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_price' => $product->price,
                        'quantity' => $product_id['product_quantity'],
                    ];
                };
            })->reject(function ($product) {
                return empty($product);
            });
        });

        return view('my-cart', [
            'products' => $cart_list,
        ]);
    }

    public function store(Request $request)
    {
        $product = $request->session()->get('_CART_', collect());

        if (isset($product) && count($product) === 0) {
            $request->session()->push('_CART_', [
                'product_id' => $request->product_id,
                'product_quantity' => $request->product_quantity,
                'product_price' => $request->product_price
            ]);
        } else {
            $productid = collect($product)->where('product_id', $request->product_id)->first();

            if ($productid != null) {
                $this->edit($request);
            } else {
                $request->session()->push('_CART_', [
                    'product_id' => $request->product_id,
                    'product_quantity' => $request->product_quantity ? $request->product_quantity : 0,
                    'product_price' => $request->product_price
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

        if($request->action === "delete") {
            $this->destroy($request);
        } else if($request->action === "update") { // for cart update
            if (isset($product) && count($product) > 0) {
                $new = collect($product)->transform(function ($value, $key) use ($request) {
                    if ($value['product_id'] === $request->product_id) {
                        $value['product_quantity'] = $request->quantity;
                    }
                    return $value;
                });
    
                $request->session()->put('_CART_', $new->toArray());
            }
        } else {
            if (isset($product) && count($product) > 0) { //for product add
                $new = collect($product)->transform(function ($value, $key) use ($request) {
                    if ($value['product_id'] === $request->product_id) {
                        $value['product_quantity'] += $request->product_quantity ? $request->product_quantity : 0;
                    }
                    return $value;
                });
    
                $request->session()->put('_CART_', $new->toArray());
            }
        }

        return response()->json([
            'result' => true,
        ]);
    }

    public function destroy(Request $request)
    {   
        $product = $request->session()->get('_CART_', collect());

        if (isset($product) && count($product) > 0) {
            $new = collect($product)->reject(function ($value, $key) use ($request) {
                return $value['product_id'] == $request->product_id;
            });

            $request->session()->put('_CART_', $new->toArray());
        }
        return response()->json([
            'result' => true,
        ]);
    }

    public function checkout(Request $request)
    {
        $input = $request->validate([
            'remarks' => 'nullable'
        ]);

        $productItems = $request->session()->get('_CART_', collect());
                
        $order = null;

        DB::transaction(function () use ($input, $productItems, $order) {
            $order = Order::create([
                'customer_id' => Auth::id(),
                'customer_name' => Auth::user()->fullname,
                'order_date' => now(),
                'remarks' => $input['remarks'],
            ]);

            $orderDetails = collect($productItems)->map(function ($item) {
                return [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['product_quantity'],
                    'unit_price' => $item['product_price'],
                    'discount' => 0
                ];
            });

            $order->line()->createMany($orderDetails->toArray());
        });
        
        $request->session()->put('_CART_', collect([]));

        return response()->json([
            'result' => true
        ]);
    }
}
