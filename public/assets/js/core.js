document.addEventListener('DOMContentLoaded', function() {
    let loadingTimer;

    // CSRF token setup for AJAX requests
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    document.addEventListener('click', function(event) {
        if (event.target.tagName === 'A') {
            const url = event.target.getAttribute('href');

            // Check if the link has the data-no-pjax attribute
            if (event.target.dataset.noPjax) {
                return true; // Allow default behavior (full page load)
            }

            event.preventDefault();
            loadContent(url);
        }
    });

    function loadContent(url) {
        clearTimeout(loadingTimer);

        // Start the progress bar after a slight delay
        loadingTimer = setTimeout(function() {
            if (typeof NProgress !== 'undefined') {
                NProgress.start(); // Start progress bar
            }
            document.getElementById('page-footer').classList.add('opacity-0');
        }, 300);

        fetch(url, {
            method: 'GET',
            headers: {
                'X-PJAX': 'true',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        })
        .then(response => response.json())
        .then(response => {
            document.getElementById('content').innerHTML = response.html;
            history.pushState(null, null, response.url);
            document.title = response.title;

            // Update meta tags for SEO
            document.querySelector('meta[name="description"]').setAttribute('content', response.metaDescription);
            document.querySelector('link[rel="canonical"]').setAttribute('href', response.canonicalUrl);

            // Reinitialize any plugins and manage focus
            initializePage();
            document.getElementById('content').focus();
        })
        .catch(error => {
            console.error("AJAX error:", error);
            if (error.name === 'TypeError') {
                window.location.href = url; // Fallback to full-page load
            } else {
                showToast('error', 'An error occurred while loading the page. Please try again.', 'Error');
            }
        })
        .finally(() => {
            clearTimeout(loadingTimer);
            if (typeof NProgress !== 'undefined') {
                NProgress.done(); // Complete progress bar
            }
            document.getElementById('loading').style.display = 'none';
            document.getElementById('page-footer').classList.remove('opacity-0');
        });
    }

    window.addEventListener('popstate', function() {
        loadContent(window.location.href);
    });

    function initializePage() {
        // Reinitialize any JS plugins or event listeners here
        // Example: Reinitialize Alpine.js, tooltips, modals, etc.

        // Handle deep linking and scroll restoration
        if (window.location.hash) {
            const target = document.querySelector(window.location.hash);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }

        // Set current year
        const currentYearElement = document.getElementById('current-year');
        if (currentYearElement) {
            currentYearElement.textContent = new Date().getFullYear();
        }

        // Add any other initialization code here
    }

    // Initial page load
    initializePage();
});

// Utility function for toast notifications (replace with your preferred method)
function showToast(type, message, title) {
    console.log(`${type.toUpperCase()}: ${title} - ${message}`);
    // Implement your preferred toast notification here
}