<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Hash;
use Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('google')->user();

        $registeredUser = null;

        $googleUser = User::whereGoogleUid($user->id);
        if ($googleUser->exists()) {
            $registeredUser = $googleUser->first();
        } else {
            $registeredUser = User::create([
                'username' => $user->id,
                'firstname' => $user->user['name']['givenName'],
                'lastname' => $user->user['name']['familyName'],
                'gender' => isset($user->user['gender']) ? strtoupper($user->user['gender']) : null,
                'email' => $user->email,
                'username' => $user->email,
                'password' => Hash::make(str_random(6)),
                'google_uid' => $user->id,
            ]);
        }

        Auth::login($registeredUser);
        return redirect('/');

    }
}
