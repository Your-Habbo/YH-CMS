@extends('layouts.auth')

@section('title', 'Set Up Two-Factor Authentication')

@section('content')
<div class="card relative flex w-full max-w-md flex-col space-y-5 bg-white px-5 py-10 sm:mx-auto rounded-lg" style="filter: drop-shadow(14px 10px 10px #4a4a4a);">
    <div class="mx-auto mb-2 space-y-3">
        <h1 class="text-center text-2xl font-bold text-gray-800">Set Up Two-Factor Authentication</h1>
        <p class="mt-2 text-center text-sm text-gray-600">
            Enhance your account security with an additional layer of protection
        </p>
    </div>

    <div class="mt-6 space-y-6">
        <div class="rounded-md shadow-sm space-y-4">
            <div>
                <p class="text-sm text-center font-medium text-gray-700 mb-2">1. Scan this QR code with your authenticator app:</p>
                <div class="flex justify-center mb-4 bg-gray-100 p-4 rounded-lg">
                    {!! $qrCodeSvg !!}
                </div>
            </div>
            <div>
                <p class="text-sm text-center font-medium text-gray-700 mb-2">2. Or manually enter this code in your app:</p>
                <p class="text-lg font-mono bg-gray-100 p-3 rounded-lg text-center select-all border border-gray-300">{{ $secretKey }}</p>
            </div>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="{{ route('two-factor.confirm-token') }}">
            @csrf
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">3. Enter the 6-digit code from your app:</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input id="code" name="code" type="text" required 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400
                                  focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="e.g., 123456" maxlength="6" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code">
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    Verify and Enable 2FA
                </button>
            </div>
        </form>

        @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4 mt-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Verification failed
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection