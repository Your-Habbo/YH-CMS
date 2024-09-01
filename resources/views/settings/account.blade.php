@pjax('layouts.app')

@section('title', 'Account settings<')

<div class="container mx-auto px-4 sm:px-6 lg:px-8 flex justify-center text-sm">
    <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-12 gap-6">
        <aside class="lg:col-span-3">
            @include('settings.sidebar')
        </aside>
        <main id="content" class="lg:col-span-9">
            <div class="card overflow-hidden sm: sm:px-8 pb-10">
                <div class="pt-4">
                    <h1 class="py-2 text-xl font-semibold">Account settings</h1> 
                </div>
                <hr class="mt-4 mb-2" />

                <!-- Email Address -->
                <section>
                <p class="py-2 text-lg font-semibold">Email Address</p> 
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-sm"> 
                    <p class="text-gray-600">Your email address is <strong>{{ $user->email }}</strong></p>
                    
                    @if ($user->email_verified_at)
                        <p class="text-green-600 font-semibold">Email Verified</p>
                    @else
                        <p class="text-red-600 font-semibold">Email Not Verified</p>
                        <form action="{{ route('verification.send') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex text-sm font-semibold text-blue-600 underline decoration-2">Resend Verification Email</button> 
                        </form>
                    @endif
                </div>
                
                <!-- Button to trigger modal -->
                <button class="inline-flex text-sm font-semibold text-blue-600 underline decoration-2" id="change-email-btn">Change</button> 
                </section>

                <hr class="mt-6 mb-6" />

                <section>
                <!-- Change Password Section -->
                <p class="py-2 text-lg font-semibold">Password</p> 
                <div class="flex items-center text-sm"> 
                    <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-3">
                        <label for="current-password">
                            <span class="text-sm text-gray-500">Current Password</span>
                            <div class="relative flex overflow-hidden rounded-md border-2 transition focus-within:border-blue-600">
                                <input type="password" id="current-password" name="current_password" class="w-full flex-shrink appearance-none border-gray-300 bg-white py-2 px-4 text-sm text-gray-700 placeholder-gray-400 focus:outline-none" placeholder="***********" /> 
                            </div>
                        </label>
                        <label for="new-password">
                            <span class="text-sm text-gray-500">New Password</span>
                            <div class="relative flex overflow-hidden rounded-md border-2 transition focus-within:border-blue-600">
                                <input type="password" id="new-password" name="password" class="w-full flex-shrink appearance-none border-gray-300 bg-white py-2 px-4 text-sm text-gray-700 placeholder-gray-400 focus:outline-none" placeholder="***********" /> 
                            </div>
                        </label>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="mt-5 ml-2 h-6 w-6 cursor-pointer text-sm font-semibold text-gray-600 underline decoration-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </div>
                <p class="mt-2 text-sm">Can't remember your current password? <a class="text-sm font-semibold text-blue-600 underline decoration-2" href="#">Recover Account</a></p>
                <button class="mt-4 rounded-lg bg-blue-600 px-4 py-2 text-white text-sm">Save Password</button>
                <hr class="mt-4 mb-8" />

                <div class="mb-10">
                </section>

                <section>
                    <p class="py-2 text-lg font-semibold">Delete Account</p> 
                    <p class="inline-flex items-center rounded-full bg-rose-100 px-4 py-1 text-rose-600 text-sm"> 
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Proceed with caution
                    </p>
                    <p class="mt-2 text-sm">Make sure you have taken a backup of your account in case you ever need to get access to your data. We will completely wipe your data. There is no way to access your account after this action.</p>
                    <button class="ml-auto text-sm font-semibold text-rose-600 underline decoration-2">Continue with deletion</button>

                </section>
                </div>
            </div>
        </main>
    </div>
</div>


<!-- Email Update Modal -->
<div id="email-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Update Email Address</h2> <!-- Reduced font size -->
        <form action="{{ route('settings.updateEmail') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">New Email Address</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" required> <!-- Reduced font size -->
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Current Password</label>
                <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" required> <!-- Reduced font size -->
            </div>
            <div class="flex justify-end">
                <button type="button" id="close-email-modal" class="mr-4 text-sm text-gray-600 underline">Cancel</button>
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-white text-sm">Update Email</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('change-email-btn').addEventListener('click', function() {
    document.getElementById('email-modal').classList.remove('hidden');
});

document.getElementById('close-email-modal').addEventListener('click', function() {
    document.getElementById('email-modal').classList.add('hidden');
});
</script>
