@component('mail::message')
# Two-Factor Authentication Disabled

Hello {{ $user->name }},

This email is to notify you that two-factor authentication has been disabled on your account. If you did not make this change, please contact our support team immediately.

@component('mail::button', ['url' => route('settings.security')])
Review Security Settings
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent