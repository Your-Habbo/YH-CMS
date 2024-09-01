
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin YourHabbo')</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/7vwtaufsqsiz5mt5lg92szt0yc49hnl04obsw5nv35l5zyfq/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @yield('styles')
</head>
<body class="">
    <main class="flex h-screen" x-data="{ openDropdown: null, openNotifications: false }">
        <!-- Sidebar -->
        
        @include('admin.layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col bg-gray-100" >
            <!-- Top Bar -->
            @include('admin.layouts.partials.topbar')

            <div id="main-content">
        {{-- This will load the content when the page is not using PJAX --}}
        {!! $content !!}
            </div>
    </main>




    @yield('scripts')

</body>

</html>
