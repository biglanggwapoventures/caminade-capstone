<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class AccountController extends Controller
{
    private $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function register(Request $request, User $user)
    {
        $input = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'contact_number' => 'required',
            'gender' => ['required', Rule::in(['MALE', 'FEMALE'])],
            'username' => ['required', Rule::unique($this->model->getTable())],
            'email' => ['required', 'email', Rule::unique($this->model->getTable())],
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ]);

        $user = $this->model->create($input);
        Auth::login($user);

        return response()->json([
            'result' => true,
            'next_url' => route('home'),
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'exists:users'],
            'password' => 'required',
        ], [
            'username.exists' => 'This username does not link to any account.',
        ]);

        if ($validator->passes()) {
            if (Auth::attempt($request->all(['username', 'password']))) {

                $nextUrl = Auth::user()->is('customer') ? route('home') : route('admin.appointment.index');

                return response()->json([
                    'result' => true,
                    'next_url' => $nextUrl,
                ]);
            } else {
                $validator->errors()->add('password', 'You entered an incorrect password');
            }
        }

        return response()->json([
            'result' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

}
