<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Auth;

class DisableTwoFactorAuthentication
{
    public function __invoke($user)
    {
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
    }
}