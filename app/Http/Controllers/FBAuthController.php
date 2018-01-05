<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Hash;
use Socialite;

class FBAuthController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('facebook')->fields([
            'name',
            'first_name',
            'middle_name',
            'last_name',
            'email',
            'gender',
            'address',
        ])->user();

        $registeredUser = null;

        $facebookUser = User::whereFacebookUid($user->id);
        if ($facebookUser->exists()) {
            $registeredUser = $facebookUser->first();
        } else {
            $registeredUser = User::create([
                'firstname' => $user->user['first_name'],
                'lastname' => $user->user['last_name'],
                'gender' => strtoupper($user->user['gender']),
                'email' => $user->email,
                'username' => $user->email,
                'password' => Hash::make(str_random(6)),
                'facebook_uid' => $user->id,
            ]);
        }

        Auth::login($registeredUser);
        return redirect('/');

    }
}
