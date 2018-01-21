<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SMSController extends Controller
{

    public function sendMessage(Request $request)
    {
        $request->validate([
            'contact_number',
        ]);

    }
}
