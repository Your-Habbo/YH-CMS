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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>

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

    // CSRF token setup for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', 'a:not([data-no-pjax])', function(event) {
        event.preventDefault();
        var url = $(this).attr('href');
        loadContent(url);
    });

    function loadContent(url) {
        clearTimeout(loadingTimer);

        // Start the progress bar after a slight delay
        loadingTimer = setTimeout(function() {
            NProgress.start(); // Start progress bar
            $('#page-footer').addClass('opacity-0');
        }, 3000);

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

                // Update meta tags for SEO
                $('meta[name="description"]').attr('content', response.metaDescription);
                $('link[rel="canonical"]').attr('href', response.canonicalUrl);

                // Reinitialize any plugins and manage focus
                initializePage();
                $('#content').focus();
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error);
                if (status === 'error') {
                    window.location.href = url; // Fallback to full-page load
                } else {
                    toastr.error('An error occurred while loading the page. Please try again.', 'Error');
                }
            },
            complete: function() {
                clearTimeout(loadingTimer);
                NProgress.done(); // Complete progress bar
                $('#loading').fadeOut(300);
                $('#page-footer').removeClass('opacity-0');
            }
        });
    }

    $(window).on('popstate', function() {
        loadContent(window.location.href);
    });

    function initializePage() {
        // Reinitialize any JS plugins or event listeners here
        // Example: Reinitialize Alpine.js, tooltips, modals, etc.

        // Trigger DOMContentLoaded event after PJAX load
        var event = new Event('DOMContentLoaded', {
            bubbles: true,
            cancelable: true
        });
        document.dispatchEvent(event);

        // Handle deep linking and scroll restoration
        if (window.location.hash) {
            var target = $(window.location.hash);
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 1000);
            }
        }
    }

    initializePage();
});

        document.getElementById('current-year').textContent = new Date().getFullYear();
    </script>
</body>
</html>
