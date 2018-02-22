<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class SMSVerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (in_array(Auth::user()->registration_method, ['Google', 'Facebook']) || Auth::user()->verified_at) {
            return $next($request);
        }

        return redirect()->route('account.show.verification-page');
    }
}
