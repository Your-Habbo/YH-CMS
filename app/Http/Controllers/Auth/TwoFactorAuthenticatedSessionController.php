<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\Models\TrustedDevice;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController as FortifyTwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;

class TwoFactorAuthenticatedSessionController extends FortifyTwoFactorAuthenticatedSessionController
{
    /**
     * Attempt to authenticate a new session using the two-factor authentication code.
     *
     * @param  \Laravel\Fortify\Http\Requests\TwoFactorLoginRequest  $request
     * @return mixed
     */
    public function store(TwoFactorLoginRequest $request)
    {
        // Call the original store method to handle the 2FA challenge
        $response = parent::store($request);

        // Add custom logic to store the trusted device
        $this->storeTrustedDevice($request);

        return $response;
    }

    /**
     * Store the trusted device if requested.
     *
     * @param  \Laravel\Fortify\Http\Requests\TwoFactorLoginRequest  $request
     * @return void
     */
    protected function storeTrustedDevice(TwoFactorLoginRequest $request)
    {
        if ($request->input('trust_device')) {
            $user = $request->challengedUser();
            $agent = new Agent();

            TrustedDevice::create([
                'user_id' => $user->id,
                'device_name' => $agent->device(),
                'device_ip' => $request->ip(),
                'device_agent' => $agent->browser() . ' on ' . $agent->platform(),
            ]);
        }
    }
}
