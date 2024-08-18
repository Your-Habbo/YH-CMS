<?php

namespace App\Actions;

use App\Models\TrustedDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

class CheckTrustedDevice
{
    public function __invoke(Request $request, $next)
    {
        Log::info('CheckTrustedDevice invoked', [
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);

        $user = User::where('email', $request->login)
                    ->orWhere('username', $request->login)
                    ->first();

        if (!$user) {
            Log::info('No user found for the provided login');
            return $next($request);
        }

        Log::info('User found in CheckTrustedDevice', ['user_id' => $user->id]);

        if (!$user->two_factor_secret || is_null($user->two_factor_confirmed_at)) {
            Log::info('User does not have 2FA enabled', ['user_id' => $user->id]);
            return $next($request);
        }

        Log::info('User has 2FA enabled', ['user_id' => $user->id]);

        $agent = new Agent();
        $agent->setUserAgent($request->header('User-Agent'));
        $browser = $agent->browser();
        $platform = $agent->platform();

        $ipParts = explode('.', $request->ip());
        $ipPrefix = implode('.', array_slice($ipParts, 0, 3));

        $trustedDevice = TrustedDevice::where('user_id', $user->id)
            ->where(function ($query) use ($ipPrefix, $browser, $platform) {
                $query->where('device_ip', 'LIKE', $ipPrefix . '%')
                      ->where('device_agent', 'LIKE', '%' . $browser . '%')
                      ->where('device_agent', 'LIKE', '%' . $platform . '%');
            })
            ->first();

            if ($trustedDevice) {
                Log::info('Trusted device found', [
                    'user_id' => $user->id,
                    'device_id' => $trustedDevice->id,
                    'device_name' => $trustedDevice->device_name
                ]);
                $request->session()->put('auth.two_factor_confirmed', true);
                Log::info('Set auth.two_factor_confirmed to true in session');
                
                // Force the session to be saved immediately
                $request->session()->save();
            } else {
                Log::info('No trusted device found for user', ['user_id' => $user->id]);
            }
    
            return $next($request);
        }
    }
