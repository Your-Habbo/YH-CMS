<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'YourHabbo')</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body>
    @include('layouts.partials.header')

    <div id="loading" style="display: none;">
        <div class="spinner"></div>
        <p>Loading... This may take a few seconds, please don't close this page.</p>
    </div>

    <main id="content" class="flex justify-center px-4 sm:px-6 lg:px-8 pt-10">
        {{-- This will load the content when the page is not using PJAX --}}
        {!! $content !!}
    </main>

    @include('layouts.partials.footer')

    <script>
$(document).ready(function() {
    $(document).on('click', 'a:not([data-no-pjax])', function(event) {
        event.preventDefault();
        var url = $(this).attr('href');
        loadContent(url);
    });

    function loadContent(url) {
        $('#loading').show(); // Show loading spinner
        $('#content').hide(); // Hide current content
        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-PJAX': 'true'
            },
            dataType: 'html',
            success: function(data) {
                $('#content').html(data).show(); // Load and show new content
                history.pushState(null, null, url);
                initializePage(); // Re-initialize any JS plugins or event listeners
            },
            complete: function() {
                setTimeout(function() {
                    $('#loading').hide();
                }, 100); // 100ms delay to ensure rendering is complete
            }
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error);
                $('#content').show(); // Show content in case of error
                alert('An error occurred while loading the page. Please try again.');
            }
        });
    }

    $(window).on('popstate', function() {
        loadContent(window.location.href);
    });

    initializePage(); // Initial call for the first load
});

    </script>
</body>
</html>
