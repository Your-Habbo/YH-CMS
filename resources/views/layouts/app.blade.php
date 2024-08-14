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

    <script>
$(document).ready(function() {
    var loadingTimer;

    $(document).on('click', 'a:not([data-no-pjax])', function(event) {
        event.preventDefault();
        var url = $(this).attr('href');
        loadContent(url);
    });

    function loadContent(url) {
        clearTimeout(loadingTimer);
        
        loadingTimer = setTimeout(function() {
            $('#loading').fadeIn(300);
            $('#page-footer').addClass('opacity-0');
        }, 300);

        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-PJAX': 'true'
            },
            dataType: 'json',
            success: function(response) {
                $('#content').html(response.html);
                history.pushState(null, null, response.url);
                document.title = response.title;
                initializePage();
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error);
                toastr.error('An error occurred while loading the page. Please try again.', 'Error');
            },
            complete: function() {
                clearTimeout(loadingTimer);
                $('#loading').fadeOut(300);
                $('#page-footer').removeClass('opacity-0');
            }
        });
    }

    $(window).on('popstate', function() {
        loadContent(window.location.href);
    });

    function initializePage() {
        // Re-initialize any JS plugins or event listeners here
    }

    initializePage();
});
</script>
<script>
    document.getElementById('current-year').textContent = new Date().getFullYear();
</script>

</body>
</html>
