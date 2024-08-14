<div class="container">
    <h2>Enable Two-Factor Authentication</h2>
    <p>Two-factor authentication adds an additional layer of security to your account.</p>
    <form method="POST" action="{{ route('two-factor.enable-token') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Enable Two-Factor Authentication</button>
    </form>
</div>