@section('title', 'Security Settings')

<div class="container mx-auto px-4 sm:px-6 lg:px-8 flex justify-center text-sm">
    <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-12 gap-6">
        <aside class="lg:col-span-3">
            @include('settings.sidebar')
        </aside>
        <main id="content" class="lg:col-span-9">
            <div class="card overflow-hidden sm: sm:px-8 pb-10">
                <div class="pt-4">
                    <h1 class="py-2 text-xl font-semibold">Security Settings</h1>
                </div>
                <hr class="mt-4 mb-2" />
                
                <!-- Active Login Sessions -->
                <section>
                    <h2 class="py-2 text-lg font-semibold">Active Login Sessions</h2>
                    <div class="space-y-4">
                        @foreach ($sessions as $session)
                            <div class="flex justify-between items-center bg-gray-100 p-3 sm:rounded-md"">
                                <div>
                                    <p class="text-xs font-medium text-gray-900">{{ $session->device }} on {{ $session->platform }}</p>
                                    <p class="text-xs text-gray-500">IP: {{ $session->ip_address }} - Last active: {{ \Carbon\Carbon::parse($session->last_active_at)->diffForHumans() }}</p>
                                </div>
                                <form action="{{ route('security.logoutSession', $session->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-800 underline">Log out</button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    <form action="{{ route('settings.logoutAllSessions') }}" method="POST" class="mt-6">
                        @csrf
                        <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Logout All Sessions</button>
                    </form>
                </section>

                <hr class="mt-6 mb-6" />

                <!-- Trusted Devices -->
                <section>
                    <h2 class="text-base font-medium text-gray-900">Trusted Devices</h2>
                    <p class="mt-1 text-xs text-gray-600">
                        Devices you've marked as trusted will not require 2FA for future logins.
                    </p>
                    <div class="mt-4 space-y-4">
                        @forelse($trustedDevices as $device)
                            <div class="flex justify-between items-center bg-gray-100 p-3 sm:rounded-md">
                                <div>
                                    <p class="text-xs font-medium text-gray-900">{{ $device->device_name }}</p>
                                    <p class="text-xs text-gray-500">IP: {{ $device->device_ip }}</p>
                                    <p class="text-xs text-gray-500">Last used: {{ $device->updated_at->format('M d, Y H:i') }}</p>
                                </div>
                                <form action="{{ route('security.removeTrustedDevice-alt', $device) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-800 underline">Revoke</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-xs text-gray-500">No trusted devices.</p>
                        @endforelse
                    </div>
                </section>

                <hr class="mt-6 mb-6" />

                <!-- Two-Factor Authentication -->
                <section>
                    <div class="mt-5 sm:mt-0">
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <div class="px-4 sm:px-0">
                                    <h3 class="text-base font-medium leading-6 text-gray-900">Two-Factor Authentication (2FA)</h3>
                                    <p class="mt-1 text-xs text-gray-600">
                                        Enhance your account security by enabling two-factor authentication.
                                    </p>
                                </div>
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2 ">
                                <div class="shadow bg-gray-100 overflow-hidden sm:rounded-md">
                                    <div class="px-4 py-5 sm:p-6 ">
                                        @if(auth()->user()->two_factor_secret)
                                            <div class="mb-4">
                                                <p class="text-xs text-gray-600">Two-Factor Authentication is currently <strong class="text-green-600">Enabled</strong>.</p>
                                            </div>
                                            <form method="POST" action="{{ route('two-factor.disable') }}" class="mb-4">
                                                @csrf
                                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    Disable 2FA
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('two-factor.reset-recovery-codes') }}" class="mb-4">
                                                @csrf
                                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-xs font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                    Reset Recovery Codes
                                                </button>
                                            </form>

                                            <div class="mt-6">
                                                <h4 class="text-base font-medium text-gray-900">Recovery Codes</h4>
                                                <p class="mt-1 text-xs text-gray-600">
                                                    Recovery codes have been generated for your account. Keep these codes in a secure location. They can be used to recover access to your account if you lose your two-factor authentication device.
                                                </p>
                                            </div>
                                        @else
                                            <div class="mb-4">
                                                <p class="text-xs text-gray-600">Two-Factor Authentication is currently <strong class="text-red-600">Disabled</strong>.</p>
                                            </div>
                                            <a href="{{ route('two-factor.setup') }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Set Up 2FA
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <hr class="mt-6 mb-6" />

                <!-- Login Alerts -->
                <section>
                    <h2 class="py-2 text-lg font-semibold">Login Alerts</h2>
                    <form action="{{ route('security.updateLoginAlerts') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="flex items-center">
                            <input type="checkbox" id="email-alerts" name="email_alerts" {{ $user->email_alerts ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="email-alerts" class="ml-2 block text-xs text-gray-900">
                                Email me whenever a new device logs into my account
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="sms-alerts" name="sms_alerts" {{ $user->sms_alerts ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="sms-alerts" class="ml-2 block text-xs text-gray-900">
                                Send me an SMS whenever a new device logs into my account
                            </label>
                        </div>
                        <button type="submit" class="mt-4 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Save Changes</button>
                    </form>
                </section>
            </div>
        </main>
    </div>
</div>

<!-- Modal for Confirming Device Removal -->
<div id="confirm-remove-device-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Confirm Removal</h2>
        <p>Are you sure you want to remove this trusted device? You may be required to go through 2FA again on your next login from this device.</p>
        <div class="flex justify-end mt-4">
            <button id="cancel-remove-device" class="mr-4 text-xs text-gray-600 hover:text-gray-800 underline">Cancel</button>
            <form id="remove-device-form" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Remove</button>
            </form>
        </div>
    </div>
</div>

<!-- Password Verification Modal -->
<div id="password-verification-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Verify Your Password</h2>
        <form id="password-verification-form" method="POST" action="{{ route('two-factor.verify-password') }}">
            @csrf
            <div class="mb-4">
                <label for="password" class="block text-xs font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div class="flex justify-end">
                <button type="button" id="cancel-verification" class="mr-4 text-xs text-gray-600 hover:text-gray-800 underline">Cancel</button>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Verify
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Recovery Codes Modal -->
<div id="recovery-codes-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden ">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Recovery Codes</h2>
        <p class="mb-4 text-xs text-gray-600">Store these recovery codes in a secure location. They can be used to recover access to your account if you lose your two-factor authentication device.</p>
        <div class="bg-gray-100 p-4 rounded-md">
            <ul class="list-disc list-inside text-xs text-gray-800 space-y-1">
                @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                    <li>{{ $code }}</li>
                @endforeach
            </ul>
        </div>
        <div class="mt-4 flex justify-end">
            <button id="close-recovery-codes" class="text-xs text-gray-600 hover:text-gray-800 underline">Close</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const removeDeviceBtns = document.querySelectorAll('.remove-device-btn');
    const confirmRemoveDeviceModal = document.getElementById('confirm-remove-device-modal');
    const cancelRemoveDeviceBtn = document.getElementById('cancel-remove-device');
    const removeDeviceForm = document.getElementById('remove-device-form');

    const viewRecoveryCodesBtn = document.getElementById('view-recovery-codes');
    const passwordVerificationModal = document.getElementById('password-verification-modal');
    const recoveryCodesModal = document.getElementById('recovery-codes-modal');
    const cancelVerificationBtn = document.getElementById('cancel-verification');
    const closeRecoveryCodesBtn = document.getElementById('close-recovery-codes');
    const passwordVerificationForm = document.getElementById('password-verification-form');

    // Trusted Devices Removal
    removeDeviceBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const deviceId = this.getAttribute('data-device-id');
            removeDeviceForm.action = `/settings/security/trusted-devices/${deviceId}`;
            confirmRemoveDeviceModal.classList.remove('hidden');
        });
    });

    cancelRemoveDeviceBtn.addEventListener('click', function() {
        confirmRemoveDeviceModal.classList.add('hidden');
    });

    // Recovery Codes Viewing
    viewRecoveryCodesBtn?.addEventListener('click', function() {
        passwordVerificationModal.classList.remove('hidden');
    });

    cancelVerificationBtn.addEventListener('click', function() {
        passwordVerificationModal.classList.add('hidden');
    });

    closeRecoveryCodesBtn.addEventListener('click', function() {
        recoveryCodesModal.classList.add('hidden');
    });

    passwordVerificationForm.addEventListener('submit', function(e) {
        e.preventDefault();
        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                password: document.getElementById('password').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                passwordVerificationModal.classList.add('hidden');
                recoveryCodesModal.classList.remove('hidden');
            } else {
                alert('Incorrect password. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
});
</script>
@endpush
