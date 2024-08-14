<h2>Set Up Token-based Two-Factor Authentication</h2>

<p>Scan this QR code with your authenticator app:</p>
<div>
    {!! $qrCodeSvg !!}
</div>

<p>Or enter this code manually: {{ $secretKey }}</p>

<form method="POST" action="{{ route('two-factor.confirm-token') }}">
    @csrf
    <label for="code">Enter the code from your authenticator app:</label>
    <input type="text" id="code" name="code" required>
    <button type="submit">Confirm</button>
</form>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif