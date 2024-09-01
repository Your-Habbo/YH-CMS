@pjax('layouts.auth')

@section('title', 'Reset Password')


<div class="card relative flex w-96 flex-col space-y-5 bg-white px-5 py-10 sm:mx-auto" style="filter: drop-shadow(14px 10px 10px #4a4a4a);">
    <div class="mx-auto mb-2 space-y-3">
        <h1 class="text-center text-l font-bold text-gray-700">Reset Password</h1>
        <p class="text-gray-500 text-xs">Create a new password for your account</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <div class="relative mt-2 w-full">
                <input type="email" id="email" name="email" required autofocus
                    class="border-1 peer block w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-2.5 pt-4 pb-2.5 text-xs text-gray-900 focus:border-gray-600 focus:outline-none focus:ring-0
                    @error('email') border-red-500 @enderror"
                    placeholder=" " value="{{ old('email', $request->email) }}" />
                <label for="email"
                    class="origin-[0] peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-gray-600 absolute left-1 top-2 z-10 -translate-y-4 scale-75 transform cursor-text select-none bg-white px-2 text-xs text-gray-500 duration-300">
                    Email Address
                </label>
                @error('email')
                    <span class="form-error text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div>
            <div class="relative mt-2 w-full">
                <input type="password" id="password" name="password" required
                    class="border-1 peer block w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-2.5 pt-4 pb-2.5 text-xs text-gray-900 focus:border-gray-600 focus:outline-none focus:ring-0
                    @error('password') border-red-500 @enderror"
                    placeholder=" " />
                <label for="password"
                    class="origin-[0] peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-gray-600 absolute left-1 top-2 z-10 -translate-y-4 scale-75 transform cursor-text select-none bg-white px-2 text-xs text-gray-500 duration-300">
                    New Password
                </label>
                @error('password')
                    <span class="form-error text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div>
            <div class="relative mt-2 w-full">
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="border-1 peer block w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-2.5 pt-4 pb-2.5 text-xs text-gray-900 focus:border-gray-600 focus:outline-none focus:ring-0"
                    placeholder=" " />
                <label for="password_confirmation"
                    class="origin-[0] peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-gray-600 absolute left-1 top-2 z-10 -translate-y-4 scale-75 transform cursor-text select-none bg-white px-2 text-xs text-gray-500 duration-300">
                    Confirm New Password
                </label>
            </div>
        </div>

        <div class="mt-6">
            <span class="block w-full rounded-md shadow-sm">
                <button type="submit"
                    class="shrink-0 inline-block w-full rounded-lg bg-gray-600 py-2 font-bold text-white text-xs hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Reset Password
                </button>
            </span>
        </div>
    </form>
</div>
