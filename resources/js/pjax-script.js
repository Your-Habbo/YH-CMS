import $ from 'jquery';
import NProgress from 'nprogress';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

function initializePage() {
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

document.addEventListener('DOMContentLoaded', function() {
    var loadingTimer;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', 'a', function(event) {
        var url = $(this).attr('href');

        if ($(this).data('no-pjax')) {
            return true;
        }

        event.preventDefault();
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

    initializePage();
});

// Set current year
document.getElementById('current-year').textContent = new Date().getFullYear();