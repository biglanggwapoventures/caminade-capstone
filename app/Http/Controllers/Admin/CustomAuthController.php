<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Rules\AdminRole;
use Auth;
use Illuminate\Http\Request;
use Validator;

class CustomAuthController extends Controller
{

    public function showLoginPage()
    {
        return view('admin.login');
    }

    public function doLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', new AdminRole],
            'password' => 'required',
        ]);

        if ($validator->passes()) {
            if (Auth::attempt($request->all(['username', 'password']))) {
                return redirect(route('admin.user.index'));
            }
            $validator->errors()->add('password', 'You entered an incorrect password');
        }

        return redirect()
            ->back()
            ->withInput()
            ->withErrors($validator);
    }
}
