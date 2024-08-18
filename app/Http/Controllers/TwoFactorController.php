<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Laravel\Fortify\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use App\Actions\Fortify\DisableTwoFactorAuthentication;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Laravel\Fortify\Features;

class TwoFactorController extends Controller
{
    protected $provider;

    public function __construct(TwoFactorAuthenticationProvider $provider)
    {
        $this->provider = $provider;
    }

    public function show(Request $request)
    {
        return $request->session()->has('login.id')
            ? view('auth.two-factor-challenge')
            : redirect()->route('login');
    }

    public function disable(Request $request, DisableTwoFactorAuthentication $disable)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }

        if ($user->two_factor_secret || $user->two_factor_confirmed_at) {
            $disable($user);
            
            $user->forceFill([
                'two_factor_confirmed_at' => null,
            ])->save();

            // Send email notification
            Mail::to($user->email)->send(new TwoFactorAuthDisabled($user));
        }

        return redirect()->route('settings.security')->with('status', 'Two-factor authentication has been disabled.');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|string',
            'recovery_code' => 'nullable|string',
        ]);

        $userId = $request->session()->pull('login.id');
        $user = \App\Models\User::find($userId);

        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Unable to find user.']);
        }

        $provider = app(TwoFactorAuthenticationProvider::class);

        if (isset($validated['code'])) {
            if (!$provider->verify(decrypt($user->two_factor_secret), $validated['code'])) {
                return back()->withErrors(['code' => 'The provided two-factor authentication code was invalid.']);
            }
        } elseif (isset($validated['recovery_code'])) {
            if (!$user->recoverTwoFactorAuthentication($validated['recovery_code'])) {
                return back()->withErrors(['recovery_code' => 'The provided two-factor recovery code was invalid.']);
            }
        } else {
            return back()->withErrors(['code' => 'You must provide a two-factor authentication code or a recovery code.']);
        }

        $remember = $request->session()->pull('login.remember', false);

        Auth::login($user, $remember);

        $request->session()->put('auth.two_factor_confirmed', true);

        return redirect()->intended(config('fortify.home'));
    }

    public function index()
    {
        return view('auth.two-factor.index');
    }

    public function choose()
    {
        return view('auth.two-factor.choose');
    }

    public function enableToken(Request $request)
    {
        $user = auth()->user();

        // Generate a new secret
        $secret = $this->provider->generateSecretKey();

        // Store the secret in the session temporarily
        $request->session()->put('temp_2fa_secret', $secret);

        $qrCodeSvg = $this->generateQrCode($user, $secret);

        Log::info('Server time when generating QR code: ' . now());

        return view('auth.two-factor.setup-token', [
            'qrCodeSvg' => $qrCodeSvg,
            'secretKey' => $secret
        ]);
    }

    public function confirmToken(Request $request, EnableTwoFactorAuthentication $enable, GenerateNewRecoveryCodes $generateNewRecoveryCodes)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = auth()->user();
        $secret = $request->session()->get('temp_2fa_secret');

        if (!$secret) {
            return back()->withErrors(['code' => 'The setup process has expired. Please start over.']);
        }

        Log::info('User: ' . $user->id);
        Log::info('Provided Code: ' . $request->code);
        Log::info('Server time: ' . now()->toDateTimeString());

        // Verify the code
        if ($this->provider->verify($secret, $request->code)) {
            // Code is valid, now we can enable 2FA
            $user->forceFill([
                'two_factor_secret' => Crypt::encrypt($secret),
                'two_factor_confirmed_at' => now(),  // Set the confirmation timestamp
            ])->save();

            $enable($user);
            $generateNewRecoveryCodes($user);

            $request->session()->forget('temp_2fa_secret');

            Log::info('Two-factor authentication enabled and confirmed successfully.');
            return redirect()->route('two-factor.recovery-codes')->with('status', 'Two-factor authentication has been enabled and confirmed.');
        }

        Log::info('Two-factor authentication confirmation failed.');
        return back()->withErrors(['code' => 'The provided two-factor authentication code was invalid.']);
    }

    private function generateQrCode($user, $secret)
    {
        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);

        $qrCodeUrl = 'otpauth://totp/'
            . urlencode(config('app.name'))
            . ':' . urlencode($user->email)
            . '?secret=' . $secret
            . '&issuer=' . urlencode(config('app.name'))
            . '&algorithm=SHA1'
            . '&digits=6'
            . '&period=30';

        return $writer->writeString($qrCodeUrl);
    }

    public function showRecoveryCodes()
    {
        return view('auth.two-factor.recovery-codes');
    }
}
