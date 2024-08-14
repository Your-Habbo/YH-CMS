<h2>Two-Factor Authentication Recovery Codes</h2>

<p>Store these recovery codes in a secure location. They can be used to recover access to your account if you lose your two-factor authentication device.</p>

<ul>
    @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
        <li>{{ $code }}</li>
    @endforeach
</ul>

<a href="{{ route('dashboard') }}">Return to Dashboard</a>