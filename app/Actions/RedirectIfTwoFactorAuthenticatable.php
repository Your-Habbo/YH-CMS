<?php

namespace App\Actions;

use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable as BaseRedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Support\Facades\Log;

class RedirectIfTwoFactorAuthenticatable extends BaseRedirectIfTwoFactorAuthenticatable
{
    public function __invoke($request, $next)
    {
        $user = $this->validateCredentials($request);

        Log::info('RedirectIfTwoFactorAuthenticatable invoked', [
            'user_id' => $user ? $user->id : null,
            'has_two_factor_secret' => $user ? !is_null($user->two_factor_secret) : false,
            'two_factor_confirmed' => $request->session()->get('auth.two_factor_confirmed', false)
        ]);

        if (optional($user)->two_factor_secret &&
            in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user))) {
            if (! $request->session()->get('auth.two_factor_confirmed')) {
                Log::info('Redirecting to 2FA challenge', ['user_id' => $user->id]);
                $this->twoFactorChallengeResponse($request, $user);

                return $this->twoFactorLoginResponse($request, $user);
            } else {
                Log::info('Skipping 2FA challenge due to trusted device', ['user_id' => $user->id]);
            }
        } else {
            Log::info('User does not have 2FA enabled or is not using TwoFactorAuthenticatable trait', [
                'user_id' => $user ? $user->id : null,
                'has_two_factor_secret' => $user ? !is_null($user->two_factor_secret) : false,
                'uses_two_factor_authenticatable' => $user ? in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user)) : false
            ]);
        }

        return $next($request);
    }
}