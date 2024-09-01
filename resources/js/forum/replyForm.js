import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

export function initializeReplyForm() {
    const replyForm = document.getElementById('reply-form');

    if (!replyForm) {
       // console.warn('Reply form not found. Skipping reply form initialization.');
        return;
    }

    if (replyForm) {
        replyForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const submitButton = document.getElementById('submit-button');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerText = 'Posting...';
            }

            const formData = new FormData(replyForm);
            const actionUrl = replyForm.getAttribute('action');
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                toastr.success('Your reply has been posted successfully!');
                // Optionally reset the form or update the page with the new reply
                replyForm.reset();
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerText = 'Post Reply';
                }
                // Optionally, update the page with the new reply content or refresh the part of the page where replies are listed
            })
            .catch(error => {
                console.error('Error posting reply:', error);
                toastr.error('An error occurred while posting your reply. Please try again.');
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerText = 'Post Reply';
                }
            });
        });
    } else {
        console.error('Reply form not found.');
    }
}
