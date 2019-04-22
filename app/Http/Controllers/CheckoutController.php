<?php

namespace App\Http\Controllers;

use App\Helpers\PayPal;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardExpirationDate;
use LVR\CreditCard\CardExpirationMonth;
use LVR\CreditCard\CardExpirationYear;
use LVR\CreditCard\CardNumber;
use Omnipay\Omnipay;
use App\Product;
use DB;
use Auth;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $yearOptions  = $this->buildYearDataFormat();
        $monthOptions = $this->buildMonthDataFormat();

        $product_session = $request->session()->get('_CART_', collect());

        $product_ids = collect($product_session);

        $products = Product::with(['category', 'logs'])
                           ->whereIn('id', $product_ids->pluck('product_id'))
                           ->get();

        $products = $product_ids->map(function ($product_id) use ($products) {
            return $products->flatMap(function ($product) use ($product_id) {
                if ($product->id == $product_id['product_id']) {
                    return [
                        'product_id'    => $product->id,
                        'product_name'  => $product->name,
                        'product_price' => $product->price,
                        'quantity'      => $product_id['product_quantity'],
                    ];
                };
            })->reject(function ($product) {
                return empty($product);
            });
        });

        return view('checkout', compact('yearOptions', 'monthOptions', 'products'));
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'firstName'       => 'required',
            'lastName'        => 'required',
            'number'          => ['required', new CardNumber],
            'expiryMonth'     => ['required', new CardExpirationMonth($request->input('expiryYear'))],
            'expiryYear'      => ['required', new CardExpirationYear($request->input('expiryMonth'))],
            'billingAddress1' => 'required',
            'billingCity'     => 'required',
            'billingPostcode' => 'required',
        ]);

        $creditCard = array_merge($input, [
            'billingCountry' => 'PH',
        ]);

        try {

            /** @var Order $order */
            $order = $this->checkout($request);

            $gateway = new PayPal();

            $gateway->setCard($creditCard);

            $payment = $gateway->pay($order->total_amount);

            if ($payment['status']) {

                $order->paypalTransaction()->create([
                    'transaction_data' => $payment['details']
                ]);

                return redirect()
                    ->route('user.order-history.show')
                    ->with(
                        'message', 'Thank you for shopping with us!'
                    );

            } else {

                return redirect()->back()->with([
                    'message' => 'Credit card unavailable. Please contact your credit card provider',
                    'debug'   => $payment['details']
                ]);
            }

        } catch (\ErrorException $e) {

            dd($e);

            return redirect()->back()->with([
                'message' => 'Credit card unavailable. Please contact your credit card provider',
                'debug'   => $e->getTrace()
            ]);

        }

    }

    /**
     * @return array
     */
    protected function buildYearDataFormat() : array
    {
        $data = [];

        $startYear = now()->subYears(10);
        $endYear   = now()->addYears(10);

        while ($startYear->lte($endYear)) {
            $yearString        = $startYear->format('y');
            $data[$yearString] = $yearString;

            $startYear = $startYear->addYear();
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function buildMonthDataFormat() : array
    {
        $data = [];

        foreach (range(1, 12) AS $month) {
            $monthString        = str_pad("{$month}", 2, "0", STR_PAD_LEFT);
            $data[$monthString] = $monthString;
        }

        return $data;
    }

    protected function checkout(Request $request)
    {
        $productItems = $request->session()->get('_CART_', collect());

        /** @var Order $order */
        $order = null;

        DB::transaction(function () use ($productItems, &$order) {
            $order = Order::create([
                'customer_id'   => Auth::id(),
                'customer_name' => Auth::user()->fullname,
                'order_date'    => now(),
                'remarks'       => '-',
            ]);

            $orderDetails = collect($productItems)->map(function ($item) {
                return [
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['product_quantity'],
                    'unit_price' => $item['product_price'],
                    'discount'   => 0
                ];
            });

            $order->line()->createMany($orderDetails->toArray());
            $order->load('line');
        });

        $request->session()->put('_CART_', collect([]));

        return $order;
    }
}
