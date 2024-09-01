@pjax('layouts.app')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h1 class="text-3xl font-extrabold text-center text-gray-900">Dashboard</h1>
            <p class="mt-4 text-center text-gray-600">Welcome, {{ auth()->user()->name }}!</p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <a href="{{ route('two-factor.index') }}">Manage Two-Factor Authentication</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <div class="mt-6">
                        <span class="block w-full rounded-md shadow-sm">
                            <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-gray-700 transition duration-150 ease-in-out">
                                Logout
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
