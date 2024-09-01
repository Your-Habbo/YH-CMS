import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

export function initializeLogoutButton() {
    const logoutForm = document.getElementById('logout-form');

    if (!logoutForm) {
        // console.warn('Logout form not found. Skipping logout button initialization.');
        return;
    }

    logoutForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (!token) {
            console.error('CSRF token not found.');
            toastr.error('CSRF token not found. Please refresh the page and try again.', 'Error');
            return;
        }

        fetch(logoutForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (response.ok) {
                toastr.success('Logged out successfully.');
                setTimeout(() => {
                    window.location.href = '/'; // Redirect to home page or login page
                }, 1000); // Optional delay to show the success message
            } else {
                return response.json().then(err => Promise.reject(err));
            }
        })
        .catch(error => {
            console.error('Logout error:', error);
            toastr.error('An error occurred while logging out. Please try again.', 'Error');
        });
    });
}
