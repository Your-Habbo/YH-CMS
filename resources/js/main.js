import $ from 'jquery';
import NProgress from 'nprogress';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

// Import the modular functions
import { initializeReplyForm } from './forum/replyForm';
import { initializeEditButtons } from './forum/editButtons';
import { initializeLikeButtons } from './forum/likeButtons';
import { initializeDeleteButtons } from './forum/deleteButtons';
import { initializeToggleSticky } from './forum/toggleSticky';
import { initializeLogoutButton } from './logoutButton';

function initializePage() {
    if (window.location.hash) {
        var target = $(window.location.hash);
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top
            }, 1000);
        }
    }

    initializeLogoutButton();
    initializeReplyForm();
    initializeEditButtons();
    initializeLikeButtons();
    initializeDeleteButtons();
    initializeToggleSticky();
}

// Simple in-memory cache object
const cache = {};

// Object to store scroll positions
const scrollPositions = {};

// Store the current layout identifier
let currentLayout = $('meta[name="layout"]').attr('content');

function processResponse(response, url, preserveUrl = false) {
    console.log(`Processing response for URL: ${url}`);

    if (typeof response === 'string') {
        try {
            response = JSON.parse(response);
        } catch (e) {
            document.open();
            document.write(response);
            document.close();
            return;
        }
    }

    if (response.layout && response.layout !== currentLayout) {
        window.location.href = url;
        return;
    }

    currentLayout = response.layout;

    if (response && response.html) {
        const contentElement = $('#main-content');
        if (contentElement.length > 0) {
            const cachedContent = cache[url] ? cache[url].html : '';
            const newContent = response.html;

            const totalLength = Math.max(cachedContent.length, newContent.length);
            let commonLength = 0;

            for (let i = 0; i < Math.min(cachedContent.length, newContent.length); i++) {
                if (cachedContent[i] === newContent[i]) {
                    commonLength++;
                }
            }

            const cachedPercentage = Math.round((commonLength / totalLength) * 100);
            const ajaxPercentage = 100 - cachedPercentage;

            console.log(`${cachedPercentage}% cached, ${ajaxPercentage}% ajax`);

            if (ajaxPercentage > 0) {
                contentElement.html(newContent);
            }

            document.title = response.title;
            $('meta[name="description"]').attr('content', response.metaDescription);
            $('link[rel="canonical"]').attr('href', response.canonicalUrl);

            initializePage();
            contentElement.focus();

            cache[url] = {
                html: newContent,
                title: response.title,
                metaDescription: response.metaDescription,
                canonicalUrl: response.canonicalUrl,
                layout: response.layout
            };

            if (!preserveUrl) {
                history.pushState({ url: url, layout: response.layout }, null, url);
            }
        } else {
            console.error('Error: #main-content element not found.');
        }
    } else if (response.redirect) {
        window.location.href = response.redirect;
    } else {
        window.location.reload();
    }
}




function getContentDifference(cachedContent, newContent) {
    const cachedLength = cachedContent.length;
    const newLength = newContent.length;

    let sameLength = 0;
    for (let i = 0; i < Math.min(cachedLength, newLength); i++) {
        if (cachedContent[i] === newContent[i]) {
            sameLength++;
        } else {
            break;
        }
    }

    return {
        cachedLength: sameLength,
        totalLength: newLength
    };
}

document.addEventListener('DOMContentLoaded', function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', 'a', function (event) {
        var url = $(this).attr('href');

        if ($(this).data('no-pjax')) {
            return true; // Allow normal behavior for links with data-no-pjax attribute
        }

        event.preventDefault();
        loadContent(url);
    });

    $(document).on('submit', 'form', function (event) {
        if ($(this).data('no-pjax')) {
            return true; // Allow normal form submission for forms with data-no-pjax attribute
        }

        event.preventDefault();
        var form = $(this);

        // Ensure the form uses POST if specified
        if (form.attr('method').toUpperCase() !== 'POST') {
            form.attr('method', 'POST');
        }

        $('.form-error').remove();

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function (response) {
                if (response.redirect) {
                    window.location.href = response.redirect;
                    return;
                }
                processResponse(response, window.location.href, true); // Preserve the current URL
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;

                    for (var field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            var errorMessage = errors[field][0];
                            var inputElement = form.find('[name="' + field + '"]');

                            inputElement.after('<span class="form-error text-xs text-red-600">' + errorMessage + '</span>');
                        }
                    }
                } else if (xhr.status === 404 || xhr.status === 403) {
                    toastr.error('Page not found or access denied. Redirecting...', 'Error');
                    setTimeout(() => {
                        window.location.href = form.attr('action');
                    }, 1500);
                } else {
                    toastr.error('An error occurred while submitting the form. Please try again.', 'Error');
                }
            }
        });
    });

    function loadContent(url, callback) {
        console.log(`Loading content for URL: ${url}`);
    
        if (cache[url] && cache[url].layout === currentLayout) {
            const cachedContent = cache[url].html;
            const contentElement = $('#main-content');
    
            contentElement.html(cachedContent);
            document.title = cache[url].title;
            $('meta[name="description"]').attr('content', cache[url].metaDescription);
            $('link[rel="canonical"]').attr('href', cache[url].canonicalUrl);
    
            initializePage();
            contentElement.focus();
            history.pushState({ url: url, layout: cache[url].layout }, null, url);
    
            console.log(`Content loaded from cache for URL: ${url}`);
    
            if (typeof callback === 'function') {
                callback();
            }
            return;
        }
    
        NProgress.start();
        $('#page-footer').addClass('opacity-0');
    
        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-PJAX': 'true'
            },
            dataType: 'html',
            success: function (response) {
                console.log(`Content loaded via AJAX for URL: ${url}`);
                processResponse(response, url);
                history.pushState({ url: url, layout: response.layout }, null, url);
    
                if (typeof callback === 'function') {
                    callback();
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 404 || xhr.status === 403) {
                    toastr.error('Page not found or access denied. Redirecting...', 'Error');
                    setTimeout(() => {
                        window.location.href = url;
                    }, 1500);
                } else {
                    console.error(`AJAX error: ${status}, ${error}`);
                    toastr.error('An error occurred while loading the page. Please try again.', 'Error');
                    if (status === 'error') {
                        window.location.href = url;
                    }
                }
            },
            complete: function () {
                NProgress.done();
                $('#loading').fadeOut(300);
                $('#page-footer').removeClass('opacity-0');
            }
        });
    }
    

    $(window).on('scroll', function () {
        scrollPositions[window.location.href] = $(window).scrollTop();
    });

    $(window).on('popstate', function (event) {
        const state = event.originalEvent.state;
        if (state) {
            if (state.layout && state.layout !== currentLayout) {
                window.location.href = state.url; // Full reload if layout has changed
            } else {
                loadContent(state.url, function () {
                    if (scrollPositions[window.location.href]) {
                        $(window).scrollTop(scrollPositions[window.location.href]);
                    }
                });
            }
        } else {
            loadContent(window.location.href);
        }
    });

    history.replaceState({ url: window.location.href, layout: currentLayout }, null, window.location.href);

    initializePage();
});

// Set current year
document.getElementById('current-year').textContent = new Date().getFullYear();
