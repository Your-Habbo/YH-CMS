export function initializeEditButtons() {

    
    //console.log('Initializing edit buttons...');
    const editButtons = document.querySelectorAll('.edit-post-btn');
    const editModal = document.getElementById('editPostModal');
    const confirmModal = document.getElementById('confirmationModal');
    const editPostContent = document.getElementById('editPostContent');
    const confirmEditPostButton = document.getElementById('confirmEditPostButton');
    const confirmButton = document.getElementById('confirmButton');
    const cancelButton = document.getElementById('cancelButton');
    const cancelEditPostButton = document.getElementById('cancelEditPostButton');
    let currentPostId = null;

    if (!editButtons.length || !editModal || !confirmModal) {
        //console.warn('Required DOM elements for editing not found. Skipping edit button initialization.');
        return;
    }
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentPostId = this.getAttribute('data-post-id');
            const postContent = this.getAttribute('data-post-content');
            editPostContent.value = postContent;
            editModal.classList.remove('hidden');
        });
    });

    if (confirmEditPostButton) {
        confirmEditPostButton.addEventListener('click', function() {
            confirmModal.classList.remove('hidden');
        });
    }

    if (cancelButton) {
        cancelButton.addEventListener('click', function() {
            confirmModal.classList.add('hidden');
        });
    }

    if (confirmButton) {
        confirmButton.addEventListener('click', function() {
            confirmModal.classList.add('hidden');
            
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            if (!token) {
                console.error('CSRF token not found');
                alert('An error occurred. Please refresh the page and try again.');
                return;
            }

            fetch(`/forum/posts/${currentPostId}/edit`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    content: editPostContent.value
                })
            }).then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            }).then(data => {
                console.log('Post edited successfully:', data);
                location.reload();
            }).catch(error => {
                console.error('Error editing post:', error);
                alert('An error occurred while editing the post: ' + (error.message || 'Please try again.'));
            });
        });
    }

    if (cancelEditPostButton) {
        cancelEditPostButton.addEventListener('click', function() {
            editModal.classList.add('hidden');
        });
    }
}