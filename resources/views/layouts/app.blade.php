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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    @include('layouts.partials.header')

    <div id="loading" style="display: none; position: fixed; top: 10px; right: 20px; z-index: 9999; background-color: rgba(0,0,0,0.7); color: white; padding: 10px 20px; border-radius: 5px; font-size: 14px;">
        <div style="display: flex; align-items: center;">
            <div class="spinner" style="width: 20px; height: 20px; border: 2px solid #fff; border-top: 2px solid #007bff; border-radius: 50%; animation: spin 1s linear infinite; margin-right: 10px;"></div>
            <span>Loading...</span>
        </div>
    </div>
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <main id="content" class="flex justify-center mt-10 px-4 sm:px-6 lg:px-8">
        {{-- This will load the content when the page is not using PJAX --}}
        {!! $content !!}
    </main>

    @include('layouts.partials.footer')
</body>
</html>
