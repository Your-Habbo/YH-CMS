@pjax('layouts.error')

@section('title', '404 - Page Not Found')


<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-xl w-full space-y-8 p-10 bg-white shadow-xl rounded-xl">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-blue-600">404</h1>
            <h2 class="mt-2 text-l font-bold text-gray-900">Oops! Page not found</h2>
            <p class="mt-2 text-sm text-gray-600">The page you are looking for doesn't exist or has been moved.</p>
        </div>
        <div class="mt-6">
            <a href="{{ route('index') }}" class=" flex items-center justify-center px-4 py-1 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-l md:px-10 transition duration-150 ease-in-out">
                Return Home
            </a>
        </div>
        <div class="mt-6 flex justify-center space-x-4">
            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">Contact Support</a>
            <span class="text-gray-300">|</span>
            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">Report an Issue</a>
        </div>
    </div>
</div>
