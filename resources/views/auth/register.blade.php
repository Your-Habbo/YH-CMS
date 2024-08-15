@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')



    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md ">
        <div class="bg-white py-8 px-5 shadow sm:rounded-lg sm:px-10" style="filter: drop-shadow(14px 10px 10px #4a4a4a);">
        <div class="sm:mx-auto sm:w-full sm:max-w-md mb-4">
        <h2 class="mt-6 text-center text-xl leading-9 font-extrabold text-gray-900">
            Create a new account
        </h2>
        <p class="mt-2 text-center text-sm leading-5 text-gray-500 max-w">
            Or
            <a href="{{ route('login') }}"
               class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150 text-xs">
                login to your account
            </a>
        </p>
    </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="grid grid-cols-1 gap-5">
                    <div class="relative w-full">
                        <input type="text" id="username" name="username" required autofocus
                               class="border-1 peer block w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-2.5 pt-4 pb-2.5 text-xs text-gray-900 focus:border-gray-600 focus:outline-none focus:ring-0
                               @error('username') border-red-500 @enderror" placeholder=" " value="{{ old('username') }}" />
                        <label for="username" class="origin-[0] peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-gray-600 absolute left-1 top-2 z-10 -translate-y-4 scale-75 transform cursor-text select-none bg-white px-2 text-xs text-gray-500 duration-300">
                            Enter Your Username
                        </label>
                        @error('username')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="relative w-full">
                        <input type="text" id="name" name="name" placeholder="John Doe"
                               class="border-1 peer block w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-2.5 pt-4 pb-2.5 text-xs text-gray-900 focus:border-gray-600 focus:outline-none focus:ring-0" />
                        <label for="name" class="origin-[0] peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-gray-600 absolute left-1 top-2 z-10 -translate-y-4 scale-75 transform cursor-text select-none bg-white px-2 text-xs text-gray-500 duration-300">
                            Display Name (Optional)
                        </label>
                    </div>

                    <div class="relative w-full">
                        <input type="date" id="dob" name="dob"
                               class="border-1 peer block w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-2.5 pt-4 pb-2.5 text-xs text-gray-900 focus:border-gray-600 focus:outline-none focus:ring-0" />
                        <label for="dob" class="origin-[0] peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-gray-600 absolute left-1 top-2 z-10 -translate-y-4 scale-75 transform cursor-text select-none bg-white px-2 text-xs text-gray-500 duration-300">
                            Date of Birth (Optional)
                        </label>
                    </div>

                    <div class="relative w-full">
                        <input type="email" id="email" name="email" required
                               class="border-1 peer block w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-2.5 pt-4 pb-2.5 text-xs text-gray-900 focus:border-gray-600 focus:outline-none focus:ring-0
                               @error('email') border-red-500 @enderror" placeholder=" " value="{{ old('email') }}" />
                        <label for="email" class="origin-[0] peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-gray-600 absolute left-1 top-2 z-10 -translate-y-4 scale-75 transform cursor-text select-none bg-white px-2 text-xs text-gray-500 duration-300">
                            Enter Your Email Address
                        </label>
                        @error('email')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="relative w-full">
                        <input type="email" id="email_confirmation" name="email_confirmation" required
                               class="border-1 peer block w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-2.5 pt-4 pb-2.5 text-xs text-gray-900 focus:border-gray-600 focus:outline-none focus:ring-0
                               @error('email_confirmation') border-red-500 @enderror" placeholder=" " />
                        <label for="email_confirmation" class="origin-[0] peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-gray-600 absolute left-1 top-2 z-10 -translate-y-4 scale-75 transform cursor-text select-none bg-white px-2 text-xs text-gray-500 duration-300">
                            Confirm Your Email Address
                        </label>
                        @error('email_confirmation')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="relative w-full">
                        <input type="password" id="password" name="password" required
                               class="border-1 peer block w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-2.5 pt-4 pb-2.5 text-xs text-gray-900 focus:border-gray-600 focus:outline-none focus:ring-0
                               @error('password') border-red-500 @enderror" placeholder=" " />
                        <label for="password" class="origin-[0] peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-gray-600 absolute left-1 top-2 z-10 -translate-y-4 scale-75 transform cursor-text select-none bg-white px-2 text-xs text-gray-500 duration-300 ">
                            Enter Your Password
                        </label>
                        @error('password')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                        <p class="mt-2 text-xs text-gray-600">
                            Password must be at least 8 characters and contain:
                            <ul class="list-disc list-inside text-xs">
                                <li>At least one uppercase letter</li>
                                <li>At least one lowercase letter</li>
                                <li>At least one number</li>
                                <li>At least one special character</li>
                            </ul>
                        </p>
                    </div>

                    <div class="relative w-full">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="border-1 peer block w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-2.5 pt-4 pb-2.5 text-xs text-gray-900 focus:border-gray-600 focus:outline-none focus:ring-0
                               @error('password_confirmation') border-red-500 @enderror" placeholder=" " />
                        <label for="password_confirmation" class="origin-[0] peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-gray-600 absolute left-1 top-2 z-10 -translate-y-4 scale-75 transform cursor-text select-none bg-white px-2 text-xs text-gray-500 duration-300">
                            Confirm Your Password
                        </label>
                        @error('password_confirmation')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <span class="block w-full rounded-md shadow-sm">
                            <button type="submit"
                                    class="shrink-0 inline-block w-full rounded-lg bg-gray-600 py-2 font-bold text-white text-xs hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Register
                            </button>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>



@endsection
