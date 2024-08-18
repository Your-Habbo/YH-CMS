@extends('layouts.auth')

@section('title', 'Two-Factor Authentication Recovery Codes')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Recovery Codes
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    For your Two-Factor Authentication
                </p>
            </div>

            <div class="mt-8">
                <div class="rounded-md bg-yellow-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Important
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>
                                    Store these recovery codes in a secure location. They are the only way to regain access to your account if you lose your two-factor authentication device.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="bg-gray-100 p-4 rounded-md">
                        <ul class="space-y-2">
                            @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                                <li class="font-mono text-sm">
                                    <span class="bg-white px-2 py-1 rounded shadow-sm select-all">{{ $code }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="mt-6">
                    <button onclick="copyRecoveryCodes()" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Copy All Codes
                    </button>
                </div>

                <div class="mt-6">
                    <a href="{{ route('index') }}" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Return to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyRecoveryCodes() {
    const codes = @json(json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true));
    const textToCopy = codes.join('\n');
    navigator.clipboard.writeText(textToCopy).then(function() {
        alert('Recovery codes copied to clipboard');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endsection