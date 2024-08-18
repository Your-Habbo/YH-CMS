<h2>Choose Two-Factor Authentication Method</h2>

<form method="POST" action="{{ route('two-factor.enable-token') }}">
    @csrf
    <button type="submit">Enable Token-based 2FA</button>
</form>

<!-- Placeholders for future methods -->
<button disabled>Email 2FA (Coming Soon)</button>
<button disabled>Physical Token (Coming Soon)</button>