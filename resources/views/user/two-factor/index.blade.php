<!-- resources/views/user/two-factor/index.blade.php -->

<h2>Two-Factor Authentication</h2>

@if(auth()->user()->two_factor_secret)
    <p>Two-factor authentication is currently enabled.</p>
    <form method="POST" action="{{ route('two-factor.disable') }}">
        @csrf
        @method('DELETE')
        <button type="submit">Disable Two-Factor Authentication</button>
    </form>
@else
    <p>Two-factor authentication is not enabled.</p>
    <a href="{{ route('two-factor.choose') }}">Enable Two-Factor Authentication</a>
@endif
