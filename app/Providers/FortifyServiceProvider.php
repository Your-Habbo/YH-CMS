<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Features;
use App\Actions\CheckTrustedDevice;
use App\Actions\RedirectIfTwoFactorAuthenticatable;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\PjaxTrait;

class FortifyServiceProvider extends ServiceProvider
{   
    use PjaxTrait;
    
    public function register()
    {
        //
    }

    public function boot()
    {   
        
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::authenticateUsing(function (Request $request) {
            Log::info('Fortify authenticateUsing called', [
                'two_factor_confirmed' => $request->session()->get('auth.two_factor_confirmed', false)
            ]);

            // Validate the login request
            $validator = Validator::make($request->all(), [
                'login' => 'required|string',
                'password' => 'required|string',
            ], [
                'login.required' => 'Please enter your username or email.',
                'password.required' => 'Please enter your password.',
            ]);

            if ($validator->fails()) {
                return null; // Allow Fortify to handle the redirection with errors
            }

            // Log the authentication attempt
            Log::info('Authentication attempt', ['login' => $request->login]);

            // Attempt to find the user by email or username
            $user = User::where('email', $request->login)
                        ->orWhere('username', $request->login)
                        ->first();

            // Log the user search result
            Log::info('User found', ['user' => $user ? $user->toArray() : null]);

            // Check the password
            if ($user && Hash::check($request->password, $user->password)) {
                Log::info('Authentication successful', [
                    'user_id' => $user->id,
                    'two_factor_confirmed' => $request->session()->get('auth.two_factor_confirmed', false)
                ]);
                return $user;
            }

            // Log the authentication failure
            Log::info('Authentication failed');

            // Add a custom error message for invalid credentials
            $validator->errors()->add('login', 'The provided credentials are incorrect.');

            return null; // Return null to indicate authentication failure and let Fortify handle the errors
        });

        Fortify::authenticateThrough(function (Request $request) {
            Log::info('Fortify authenticateThrough called');
            $pipeline = array_filter([
                config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
                Features::enabled(Features::twoFactorAuthentication()) ? CheckTrustedDevice::class : null,
                Features::enabled(Features::twoFactorAuthentication()) ? RedirectIfTwoFactorAuthenticatable::class : null,
                AttemptToAuthenticate::class,
                PrepareAuthenticatedSession::class,
            ]);
            Log::info('Authentication pipeline', ['pipeline' => $pipeline]);
            return $pipeline;
        });

        Fortify::loginView(function () {
            return $this->view('auth.login');
        });

        Fortify::registerView(function () {
            return $this->view('auth.register');
        });

        Fortify::twoFactorChallengeView(function () {
            return $this->view('auth.two-factor-challenge');
        });

        RateLimiter::for('login', function (Request $request) {
            $login = (string) $request->login;
            return Limit::perMinute(5)->by($login.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}