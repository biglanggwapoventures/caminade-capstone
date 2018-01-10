<?php

namespace App\Http\Controllers;

use Auth;

class LogoutController extends Controller
{
    public function __invoke()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
