<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Laravel\Fortify\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use App\Actions\Fortify\DisableTwoFactorAuthentication;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Laravel\Fortify\Features;

class TwoFactorController extends Controller
{
    public function show(Request $request)
    {
        return $request->session()->has('login.id')
            ? view('auth.two-factor-challenge')
            : redirect()->route('login');
    }

    public function disable(Request $request, DisableTwoFactorAuthentication $disable)
    {
        $user = $request->user();

        if ($user->two_factor_secret) {
            $disable($user);
        }

        return redirect()->route('two-factor.index')->with('status', 'Two-factor authentication disabled successfully.');
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
        return view('user.two-factor.index');
    }

    public function choose()
    {
        return view('user.two-factor.choose');
    }

    public function enableToken(Request $request, EnableTwoFactorAuthentication $enable)
    {
        $user = auth()->user();

        if ($request->isMethod('post')) {
            $enable($user);
        }

        if ($user->two_factor_secret) {
            $qrCodeSvg = $this->generateQrCode($user);
            $secretKey = decrypt($user->two_factor_secret);

            Log::info('Server time when generating QR code: ' . now());
            Log::info('Secret Key: ' . $secretKey);

            return view('user.two-factor.setup-token', [
                'qrCodeSvg' => $qrCodeSvg,
                'secretKey' => $secretKey  // Pass the decrypted secret to the view
            ]);
        }

        return view('user.two-factor.enable-token');
    }

    private function generateQrCode($user)
    {
        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);

        $twoFactorSecret = decrypt($user->two_factor_secret);
        Log::info('QR Code Secret: ' . $twoFactorSecret);  // Debug log

        $qrCodeUrl = 'otpauth://totp/'
            . urlencode(config('app.name'))
            . ':' . urlencode($user->email)
            . '?secret=' . $twoFactorSecret
            . '&issuer=' . urlencode(config('app.name'))
            . '&algorithm=SHA1'
            . '&digits=6'
            . '&period=30';

        return $writer->writeString($qrCodeUrl);
    }

    public function confirmToken(Request $request, ConfirmTwoFactorAuthentication $confirm)
    {
        $validated = $request->validate([
            'code' => 'required|string',
        ]);

        $user = auth()->user();

        Log::info('User: ' . $user->id);
        Log::info('Provided Code: ' . $validated['code']);
        Log::info('Server time: ' . now()->toDateTimeString());

        $provider = app(TwoFactorAuthenticationProvider::class);

        // Try with a window of 1 before and 1 after
        for ($i = -1; $i <= 1; $i++) {
            $valid = $provider->verify(
                decrypt($user->two_factor_secret),
                $validated['code'],
                $i
            );

            Log::info('Verification attempt ' . ($i + 2) . ' result: ' . ($valid ? 'success' : 'failure'));

            if ($valid) {
                if (Config::get('fortify.features') && in_array(Features::twoFactorAuthentication(), Config::get('fortify.features'))) {
                    $user->forceFill([
                        'two_factor_confirmed_at' => now(),
                    ])->save();
                }

                Log::info('Two-factor authentication confirmed successfully.');
                return redirect()->route('two-factor.recovery-codes');
            }
        }

        Log::info('Two-factor authentication confirmation failed after all attempts.');
        return back()->withErrors(['code' => 'The provided two-factor authentication code was invalid.']);
    }

    public function showRecoveryCodes()
    {
        return view('user.two-factor.recovery-codes');
    }
}
