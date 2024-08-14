<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Log user details and the current URL
        Log::debug('TwoFactorMiddleware invoked', [
            'user_id' => $user ? $user->id : null,
            'two_factor_secret' => $user ? $user->two_factor_secret : null,
            'two_factor_confirmed_at' => $user ? $user->two_factor_confirmed_at : null,
            'url' => $request->url(),
            'session' => $request->session()->all(),
        ]);

        // Proceed with request if user is not authenticated or does not have 2FA setup
        if (!$user || !$user->two_factor_secret) {
            Log::debug('Proceeding with request as user is not authenticated or does not have 2FA setup');
            return $next($request);
        }

        // If user has 2FA secret but it's not confirmed, allow request to proceed
        if ($user->two_factor_secret && !$user->two_factor_confirmed_at) {
            Log::debug('User has 2FA secret but not confirmed, proceeding with request');
            return $next($request);
        }

        // If 2FA is confirmed, ensure session has 2FA confirmed key
        if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
            if ($request->session()->has('auth.two_factor_confirmed')) {
                Log::debug('2FA confirmed in session, proceeding with request');
                return $next($request);
            }

            // Redirect to the two-factor challenge if not already on it and not logging out
            if (!$request->is('two-factor-challenge') && !$request->is('logout')) {
                Log::debug('Redirecting to two-factor challenge');
                return redirect()->route('two-factor.challenge');
            }
        }

        Log::debug('Proceeding with request');
        return $next($request);
    }
}
