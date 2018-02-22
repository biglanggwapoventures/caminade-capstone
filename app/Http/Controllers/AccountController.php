<?php

namespace App\Http\Controllers;

use App\Facades\SMS;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Toast;
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
            '',
        ]);

        $input['verification_code'] = uniqid();

        $user = $this->model->create($input);
        $message = new SMS($user->contact_number, "PetCare: Verification Code: {$input['verification_code']}");
        $message->send();

        Auth::login($user);

        // return redirect()->

        // Toast::success("Welcome to PetCare, {$user->fullname}!");

        return response()->json([
            'result' => true,
            'next_url' => route('account.show.verification-page'),
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
            $credentials = $request->all(['username', 'password']);
            if (Auth::attempt($credentials)) {
                Toast::success("Welcome back, " . Auth::user()->fullname . " !");
                if (Auth::user()->is_blocked) {
                    $validator->errors()->add('username', 'This account has been blocked!');
                    Auth::logout();
                } else {
                    return response()->json([
                        'result' => true,
                        'next_url' => $this->getNextUrl(),
                    ]);
                }
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
        Toast::success("You have been successfully logged out!");
        return redirect()->route('home');
    }

    protected function getNextUrl()
    {
        switch (strtolower(Auth::user()->role)) {
            case 'doctor':
                return route('doctor.appointment.index');
            case 'customer':
                return route('home');
            default:
                return route('admin.appointment.index');
        }
    }

    public function update(Request $request)
    {
        $input = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'contact_number' => 'required',
            'address' => 'required',
            'gender' => ['required', Rule::in(['MALE', 'FEMALE'])],
            'email' => ['required', 'email', Rule::unique($this->model->getTable())->ignore(Auth::id())],
            'password' => 'nullable|min:6',
            'password_confirmation' => 'nullable|same:password',
        ]);

        if (!trim($input['password'])) {
            unset($input['password'], $input['password_confirmation']);
        }

        Auth::user()->fill($input)->save();
        Toast::success("Your profile been successfully updated!");

        return response()->json([
            'result' => true,
        ]);
    }

}
