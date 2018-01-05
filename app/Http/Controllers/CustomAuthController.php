<?php

namespace App\Http\Controllers;

class CustomAuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }
}
