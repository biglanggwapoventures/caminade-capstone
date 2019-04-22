<?php

namespace App\Http\Controllers;

use App\Helpers\PayPal;
use Illuminate\Http\Request;

/**
 * Class PayPalController
 * @package App\Http\Controllers
 */
class PayPalController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function form(Request $request)
    {
        return (new PayPal())->pay();
    }


    /**
     * @param         $order_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkout($order_id, Request $request)
    {

        $paypal = new PayPal();

        $response = $paypal->purchase([
            'amount'        => 10000,
            'transactionId' => $order_id,
            'currency'      => 'USD',
            'cancelUrl'     => '#',
            'returnUrl'     => '#'
        ]);

        if ($response->isRedirect()) {
            $response->redirect();
        }

        return redirect()->back()->with([
            'message' => $response->getMessage(),
        ]);
    }

    /**
     * @param         $order_id
     * @param Request $request
     * @return mixed
     */
    public function completed($order_id, Request $request)
    {
        $order = Order::findOrFail($order_id);

        $paypal = new PayPal;

        $response = $paypal->complete([
            'amount'        => $paypal->formatAmount($order->amount),
            'transactionId' => $order->id,
            'currency'      => 'PHP',
            'cancelUrl'     => $paypal->getCancelUrl($order),
            'returnUrl'     => $paypal->getReturnUrl($order),
            'notifyUrl'     => $paypal->getNotifyUrl($order),
        ]);

        if ($response->isSuccessful()) {
            $order->update([
                'transaction_id' => $response->getTransactionReference(),
                'payment_status' => Order::PAYMENT_COMPLETED,
            ]);

            return redirect()->route('order.paypal', encrypt($order_id))->with([
                'message' => 'You recent payment is sucessful with reference code ' . $response->getTransactionReference(),
            ]);
        }

        return redirect()->back()->with([
            'message' => $response->getMessage(),
        ]);
    }

    /**
     * @param $order_id
     */
    public function cancelled($order_id)
    {
        $order = Order::findOrFail($order_id);

        return redirect()->route('order.paypal', encrypt($order_id))->with([
            'message' => 'You have cancelled your recent PayPal payment !',
        ]);
    }

    /**
     * @param         $order_id
     * @param         $env
     * @param Request $request
     */
    public function webhook($order_id, $env, Request $request)
    {
        // to do with new release of sudiptpa/paypal-ipn v3.0 (under development)
    }
}