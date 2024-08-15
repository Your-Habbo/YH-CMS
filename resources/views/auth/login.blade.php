@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<!-- Login -->
<div class="card relative flex w-96 flex-col space-y-5 bg-white px-5 py-10 sm:mx-auto" style="filter: drop-shadow(14px 10px 10px #4a4a4a);">
    <div class="mx-auto mb-2 space-y-3">
        <h1 class="text-center text-l font-bold text-gray-700">Sign in</h1>
        <p class="text-gray-500 text-xs">Sign in to access your account</p>
    </div>

    <form method="POST" action="{{ route('login.store') }}">
        @csrf
        <div>
            <div class="relative mt-2 w-full">
                <input type="text" id="login" name="login" required autofocus
                    class="border-1 peer block w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-2.5 pt-4 pb-2.5 text-xs text-gray-900 focus:border-gray-600 focus:outline-none focus:ring-0
                    @error('login') border-red-500 @enderror"
                    placeholder=" " value="{{ old('login') }}" />
                <label for="login"
                    class="origin-[0] peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-gray-600 absolute left-1 top-2 z-10 -translate-y-4 scale-75 transform cursor-text select-none bg-white px-2 text-xs text-gray-500 duration-300">
                    Enter Your Username or Email
                </label>
                @error('login')
                    <span class="text-xs text-red-600">{{ $message }}</span>
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
                    Enter Your Password
                </label>
                @error('password')
                    <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember_me" name="remember" type="checkbox"
                    class="form-checkbox h-4 w-4 text-gray-600 transition duration-150 ease-in-out">
                <label for="remember_me" class="ml-2 block text-xs leading-5 text-gray-900">
                    Remember me
                </label>
            </div>

            <div class="text-sm leading-5">
                <a href="{{ route('password.request') }}"
                    class="text-xs text-gray-600 hover:text-gray-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                    Forgot your password?
                </a>
            </div>
        </div>

        <div class="mt-6">
            <span class="block w-full rounded-md shadow-sm">
                <button type="submit"
                    class="shrink-0 inline-block w-full rounded-lg bg-gray-600 py-2 font-bold text-white text-xs hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Sign in
                </button>
            </span>
        </div>
    </form>

    <div class="mt-6">
        <p class="text-center text-xs text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}"
                class="whitespace-nowrap font-semibold text-gray-900 hover:underline">
                Sign up
            </a>
        </p>
    </div>
</div>
<!-- /Login -->

    @endsection