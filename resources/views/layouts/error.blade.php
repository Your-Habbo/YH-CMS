<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'YourHabbo')</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{ asset('assets/img/apple-touch-icon.png{') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{ asset('assets/img/avicon-16x16.png') }}">
    <link rel="manifest" href="/site.webmanifest">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body>



    <main id="content" class="flex justify-center mt-10 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    @include('layouts.partials.footer')
    

</body>
</html>
