<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class ViewOrderHistoryController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('user.order-history', [
            'data' => Auth::user()->ordersWithDetails,
        ]);
    }
}
