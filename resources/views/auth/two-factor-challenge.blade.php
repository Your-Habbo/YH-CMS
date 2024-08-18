@extends('layouts.auth')

@section('title', 'Two-Factor Challenge')

@section('content')
<div class="card relative flex w-full max-w-md flex-col space-y-5 bg-white px-5 py-10 sm:mx-auto rounded-lg" style="filter: drop-shadow(14px 10px 10px #4a4a4a);">
    <div class="mx-auto mb-2 space-y-3">
        <h1 class="text-center text-2xl font-bold text-gray-800">Set Up Two-Factor Authentication</h1>
        <p class="mt-2 text-center text-sm text-gray-600">
            Two-Factor Authentication
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Please enter your authentication code or use a recovery code to login.
        </p>
    </div>

    <div x-data="{ activeTab: 'code' }" class="mt-8">
        <div class="flex border-b border-gray-200">
            <button @click="activeTab = 'code'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'code' }" class="py-2 px-4 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                Authentication Code
            </button>
            <button @click="activeTab = 'recovery'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'recovery' }" class="ml-8 py-2 px-4 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                Recovery Code
            </button>
        </div>

        <div class="mt-8">
            <form action="{{ route('two-factor.login.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div x-show="activeTab === 'code'" class="p-6 rounded-lg space-y-6">
                    <!-- Authentication Code Section -->
                    <div class="flex flex-col space-y-2">
                        <label for="code" class="text-sm font-semibold text-gray-800">Authentication Code</label>
                        <input 
                            id="code" 
                            name="code" 
                            type="text" 
                            inputmode="numeric" 
                            autocomplete="one-time-code" 
                            class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Enter your 6-digit code"
                        >
                    </div>
                    
                    <!-- Trust Device Checkbox Section -->
                    <div class="flex items-center">
                        <input 
                            id="trust_device" 
                            name="trust_device" 
                            type="checkbox" 
                            value="1" 
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" 
                        >
                        <label for="trust_device" class="ml-2 text-sm text-gray-700">
                            Trust this device
                        </label>
                    </div>
                </div>
                
                <div x-show="activeTab === 'recovery'" x-cloak>
                    <label for="recovery_code" class="block text-sm font-medium text-gray-700">Recovery Code</label>
                    <input id="recovery_code" name="recovery_code" type="text" autocomplete="off" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter a recovery code">
                </div>
            
                @if ($errors->any())
                    <div class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    There were errors with your submission
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
            
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Verify and Login
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>
</div>
@endsection
