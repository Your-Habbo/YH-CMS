export function initializeDeleteButtons() {
    const deleteButtons = document.querySelectorAll('.delete-post-btn');

    if (!deleteButtons.length) {
        //console.warn('Delete buttons not found. Skipping delete button initialization.');
        return;
    }
    //console.log('Initializing delete buttons...');

    const confirmationModal = document.getElementById('confirmationModal');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const confirmButton = document.getElementById('confirmButton');
    const cancelButton = document.getElementById('cancelButton');

    // Function to show the modal
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

    // Handle delete thread
    document.querySelectorAll('.delete-thread-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const threadId = this.getAttribute('data-thread-id');
            const url = `/forum/threads/${threadId}`;
            const threadElement = this.closest('.thread-item');

            const deleteAction = () => {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                }).then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.error || 'An error occurred while deleting the thread.');
                        });
                    }
                    return response.json();
                }).then(data => {
                    console.log(data.message);
                    hideModal();
                    if (threadElement) {
                        threadElement.remove();
                    } else {
                        window.location.href = '/forum';
                    }
                }).catch(error => {
                    console.error('Error deleting thread:', error.message);
                    alert(error.message);
                });
            };

            showModal(
                'Confirm Deletion',
                'Are you sure you want to delete this thread?',
                'This action and not be revrsed!',
                deleteAction
            );
        });
    });

    // Handle delete post
    document.querySelectorAll('.delete-post-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const postId = this.getAttribute('data-post-id');
            const url = `/forum/posts/${postId}`;
            const postElement = this.closest('.post-item');

            const deleteAction = () => {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                }).then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.error || 'An error occurred while deleting the post.');
                        });
                    }
                    return response.json();
                }).then(data => {
                    console.log(data.message);
                    hideModal();
                    if (postElement) {
                        postElement.classList.add('deleted-post');
                        const contentElement = postElement.querySelector('.post-content');
                        if (contentElement) {
                            contentElement.innerHTML = '<em>This post has been deleted.</em>';
                        }
                        this.style.display = 'none';
                        const messageElement = document.createElement('div');
                        messageElement.textContent = 'Post deleted successfully.';
                        messageElement.className = 'text-green-500 mt-2';
                        postElement.appendChild(messageElement);
                    } else {
                        window.location.reload();
                    }
                }).catch(error => {
                    console.error('Error deleting post:', error.message);
                    alert('Error: ' + error.message);
                });
            };

            showModal(
                'Confirm Deletion',
                'Are you sure you want to delete this post?',
                'This action and not be revrsed!',
                deleteAction
            );
        });
    });
}
