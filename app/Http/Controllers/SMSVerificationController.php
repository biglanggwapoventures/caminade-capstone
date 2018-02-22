<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Toast;

class SMSVerificationController extends Controller
{
    public function showPage()
    {
        return view('sms-verification');
    }

    public function doVerify(Request $request)
    {
        $this->validate($request, [
            'code' => ['required', Rule::exists('users', 'verification_code')->where(function ($q) {
                $q->whereId(Auth::id());
            })],
        ]);

        Auth::user()->update([
            'verified_at' => now(),
        ]);

        Toast::success('You have been verified!');

        return redirect()->route('home');

    }
}
