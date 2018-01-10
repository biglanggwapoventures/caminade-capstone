<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Validator;

class CustomAuthController extends Controller
{
    public function doLogin(Request $request)
    {
        // return 'pota';
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'exists:users'],
            'password' => 'required',
        ], [
            'username.exists' => 'This username does not link to any account.',
        ]);

        if ($validator->passes() && Auth::attempt($request->all(['username', 'password']))) {
            return response()->json([
                'result' => true,
                'next_url' => route('home'),
            ]);
        }

        $validator->errors()->add('password', 'You entered an incorrect password');

        return response()->json([
            'result' => false,
            'errors' => $validator->errors(),
        ], 422);
    }
}
