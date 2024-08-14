<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Challenge</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <img class="mx-auto h-10 w-auto" src="https://www.svgrepo.com/show/301692/login.svg" alt="Workflow">
            <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
                Two-Factor Challenge
            </h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form method="POST" action="{{ url('/two-factor-challenge') }}">
                    @csrf
                    <div>
                        <label for="code" class="block text-sm font-medium leading-5 text-gray-700">Verification Code</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input id="code" name="code" type="text" inputmode="numeric" required autofocus
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                        </div>
                    </div>

                    <div class="mt-6">
                        <span class="block w-full rounded-md shadow-sm">
                            <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                                Verify
                            </button>
                        </span>
                    </div>
                </form>

                <!-- Debugging Information -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Debugging Information</h3>
                    <div class="mt-2 text-sm text-gray-600">
                        <p>CSRF Token: {{ csrf_token() }}</p>
                        <p>Session ID: {{ session()->getId() }}</p>
                        <p>Auth User ID: {{ auth()->user()->id ?? 'Guest' }}</p>
                        <p>Session Data: {{ json_encode(session()->all()) }}</p>
                    </div>
                </div>
                <!-- End Debugging Information -->
            </div>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
