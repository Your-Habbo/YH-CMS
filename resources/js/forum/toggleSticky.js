import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

export function initializeToggleSticky() {
    //console.log('Initializing toggle sticky button...');

    const toggleStickyBtn = document.getElementById('toggleStickyBtn');
    const confirmationModal = document.getElementById('confirmationModal');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const confirmButton = document.getElementById('confirmButton');
    const cancelButton = document.getElementById('cancelButton');

    if (!toggleStickyBtn || !confirmationModal) {
        //console.error('Required DOM elements are missing');
        return;
    }

    // Function to show the modal with dynamic content
    function showModal(title, message, action) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        confirmButton.onclick = action;
        confirmationModal.classList.remove('hidden');
    }

    // Function to hide the modal
    function hideModal() {
        confirmationModal.classList.add('hidden');
    }

    // Attach hide functionality to the cancel button
    cancelButton.addEventListener('click', hideModal);

    // Attach event listener to the toggle sticky button
    toggleStickyBtn.addEventListener('click', function() {
        const threadId = this.getAttribute('data-thread-id');
        console.log('Thread ID:', threadId);  // Debugging line

        if (!threadId) {
            console.error('Thread ID is undefined or not set.');
            toastr.error('An error occurred: Thread ID is missing.');
            return;
        }

        // Prepare the action for the modal's confirm button
        const toggleStickyAction = () => {
            fetch(`/forum/threads/${threadId}/toggle-sticky`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                const btnText = document.getElementById('stickyBtnText');
                btnText.textContent = data.is_sticky ? 'Unsticky' : 'Sticky';
                hideModal();
                toastr.success(data.message);  // Show success toast
            })
            .catch(error => {
                console.error('Error:', error);
                hideModal();
                toastr.error('An error occurred while updating the thread status.');  // Show error toast
            });
        };

        // Show the confirmation modal with appropriate content
        showModal(
            'Confirm Toggle Sticky',
            'Are you sure you want to change the sticky status of this thread?',
            toggleStickyAction
        );
    });
}
