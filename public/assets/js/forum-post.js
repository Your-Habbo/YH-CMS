
document.addEventListener('DOMContentLoaded', function() {
    const editPostModal = document.getElementById('editPostModal');
    const editThreadModal = document.getElementById('editThreadModal');
    const historyModal = document.getElementById('editHistoryModal');
    const editPostBtns = document.querySelectorAll('.edit-post-btn');
    const editThreadBtn = document.querySelector('.edit-thread-btn');
    const historyPostBtns = document.querySelectorAll('.view-history-btn');
    const historyThreadBtn = document.querySelector('.view-thread-history-btn');
    const cancelEditPostBtn = document.getElementById('cancelEditBtn');
    const cancelEditThreadBtn = document.getElementById('cancelThreadEditBtn');
    const closeHistoryBtn = document.getElementById('closeHistoryBtn');
    const saveEditPostBtn = document.getElementById('saveEditBtn');
    const saveEditThreadBtn = document.getElementById('saveThreadEditBtn');
    const postContentTextarea = document.getElementById('editPostContent');
    const threadTitleInput = document.getElementById('editThreadTitle');
    const threadContentTextarea = document.getElementById('editThreadContent');
    const historyContent = document.getElementById('editHistoryContent');
    let currentPostId = null;
    let currentThreadId = null;
    let unlikeTargetElement = null;
    let unlikeType = null;

    function getCsrfToken() {
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        if (tokenMeta) {
            return tokenMeta.getAttribute('content');
        }
        console.error('CSRF token meta tag not found');
        return null;
    }

    function makeRequest(url, method, data) {
        const csrfToken = getCsrfToken();
        if (!csrfToken) {
            alert('Error: CSRF token not found. Please refresh the page and try again.');
            return Promise.reject('CSRF token not found');
        }

        return fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: method !== 'GET' ? JSON.stringify(data) : undefined
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Server response:', text);
                    throw new Error(`Server responded with ${response.status}: ${text}`);
                });
            }
            return response.json();
        });
    }

        // Like buttons for threads
    document.querySelectorAll('.like-thread-btn').forEach(button => {
        button.addEventListener('click', function() {
            const threadId = this.dataset.threadId;
            fetch(`/threads/${threadId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                this.querySelector('.likes-count').textContent = data.likes_count;
            })
            .catch(error => console.error('Error liking thread:', error));
        });
    });

    // Like buttons for posts
    document.querySelectorAll('.like-post-btn').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                this.querySelector('.likes-count').textContent = data.likes_count;
            })
            .catch(error => console.error('Error liking post:', error));
        });
    });

    // Share buttons for threads
    document.querySelectorAll('.share-thread-btn').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.dataset.threadUrl;
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: url
                }).then(() => console.log('Shared successfully'))
                .catch(error => console.error('Error sharing:', error));
            } else {
                alert('Sharing is not supported on this browser.');
            }
        });
    });

    editPostBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            currentPostId = this.dataset.postId;
            const postContent = this.closest('.post-item').querySelector('p').textContent;
            postContentTextarea.value = postContent;
            if (editPostModal) {
                editPostModal.classList.remove('hidden');
            }
        });
    });

    if (editThreadBtn) {
        editThreadBtn.addEventListener('click', function() {
            currentThreadId = this.dataset.threadId;
            const threadTitle = document.querySelector('.card-header h3').textContent.trim();
            const threadContent = document.querySelector('.thread-content p').textContent.trim();
            threadTitleInput.value = threadTitle;
            threadContentTextarea.value = threadContent;
            if (editThreadModal) {
                editThreadModal.classList.remove('hidden');
            }
        });
    }

    historyPostBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.dataset.postId;
            fetchPostEditHistory(postId);
        });
    });

    if (historyThreadBtn) {
        historyThreadBtn.addEventListener('click', function() {
            const threadId = this.dataset.threadId;
            fetchThreadEditHistory(threadId);
        });
    }

    if (cancelEditPostBtn) {
        cancelEditPostBtn.addEventListener('click', function() {
            if (editPostModal) {
                editPostModal.classList.add('hidden');
            }
        });
    }

    if (cancelEditThreadBtn) {
        cancelEditThreadBtn.addEventListener('click', function() {
            if (editThreadModal) {
                editThreadModal.classList.add('hidden');
            }
        });
    }

    if (closeHistoryBtn) {
        closeHistoryBtn.addEventListener('click', function() {
            if (historyModal) {
                historyModal.classList.add('hidden');
            }
        });
    }

    if (saveEditPostBtn) {
        saveEditPostBtn.addEventListener('click', function() {
            if (currentPostId) {
                makeRequest(`/forum/posts/${currentPostId}/edit`, 'POST', {
                    content: postContentTextarea.value
                })
                .then(data => {
                    if (data.success) {
                        const postElement = document.querySelector(`.edit-post-btn[data-post-id="${currentPostId}"]`)
                            .closest('.post-item')
                            .querySelector('p');
                        postElement.textContent = postContentTextarea.value;
                        
                        const postMeta = postElement.nextElementSibling;
                        if (!postMeta.textContent.includes('(Edited)')) {
                            const editedSpan = document.createElement('span');
                            editedSpan.className = 'ml-2 text-xs text-gray-500';
                            editedSpan.textContent = '(Edited)';
                            postMeta.querySelector('span').appendChild(editedSpan);
                        }
                        
                        if (editPostModal) {
                            editPostModal.classList.add('hidden');
                        }
                    } else {
                        alert('Failed to update post. Server response indicates failure.');
                    }
                })
                .catch(error => {
                    console.error('Full error:', error);
                    alert(`An error occurred while updating the post: ${error.message}`);
                });
            }
        });
    }

    if (saveEditThreadBtn) {
        saveEditThreadBtn.addEventListener('click', function() {
            if (currentThreadId) {
                makeRequest(`/forum/threads/${currentThreadId}/edit`, 'POST', {
                    title: threadTitleInput.value,
                    content: threadContentTextarea.value
                })
                .then(data => {
                    if (data.success) {
                        document.querySelector('.card-header h3').textContent = threadTitleInput.value;
                        document.querySelector('.thread-content p').textContent = threadContentTextarea.value;
                        
                        const threadMeta = document.querySelector('.thread-meta');
                        if (!threadMeta.textContent.includes('(Edited)')) {
                            const editedSpan = document.createElement('span');
                            editedSpan.className = 'ml-2 text-xs text-gray-500';
                            editedSpan.textContent = '(Edited)';
                            threadMeta.appendChild(editedSpan);
                        }
                        
                        if (editThreadModal) {
                            editThreadModal.classList.add('hidden');
                        }
                    } else {
                        alert('Failed to update thread. Server response indicates failure.');
                    }
                })
                .catch(error => {
                    console.error('Full error:', error);
                    alert(`An error occurred while updating the thread: ${error.message}`);
                });
            }
        });
    }

    function fetchPostEditHistory(postId) {
        makeRequest(`/forum/posts/${postId}/history`, 'GET')
            .then(data => {
                displayEditHistory(data, false);
                if (historyModal) {
                    historyModal.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Full error:', error);
                alert(`Failed to fetch post edit history: ${error.message}`);
            });
    }

    function fetchThreadEditHistory(threadId) {
        makeRequest(`/forum/threads/${threadId}/history`, 'GET')
            .then(data => {
                displayEditHistory(data, true);
                if (historyModal) {
                    historyModal.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Full error:', error);
                alert(`Failed to fetch thread edit history: ${error.message}`);
            });
    }

    function displayEditHistory(history, isThread = false) {
        historyContent.innerHTML = '';
        if (history.length === 0) {
            historyContent.innerHTML = '<p>No edit history available.</p>';
            return;
        }

        history.forEach((edit, index) => {
            const editDiv = document.createElement('div');
            editDiv.className = 'mb-4 pb-4 border-b';
            let editContent = '';
            if (isThread) {
                editContent = `
                    <h4 class="text-md font-semibold mb-2">
                        Edit ${history.length - index} by ${edit.user.name} (${new Date(edit.created_at).toLocaleString()})
                    </h4>
                    <div class="diff-view" data-old-title="${encodeURIComponent(edit.old_title)}" data-new-title="${encodeURIComponent(edit.new_title)}" data-old-content="${encodeURIComponent(edit.old_content)}" data-new-content="${encodeURIComponent(edit.new_content)}"></div>
                `;
            } else {
                editContent = `
                    <h4 class="text-md font-semibold mb-2">
                        Edit ${history.length - index} by ${edit.user.name} (${new Date(edit.created_at).toLocaleString()})
                    </h4>
                    <div class="diff-view" data-old-content="${encodeURIComponent(edit.old_content)}" data-new-content="${encodeURIComponent(edit.new_content)}"></div>
                `;
            }
            editDiv.innerHTML = editContent;
            historyContent.appendChild(editDiv);
        });

        const diffViews = historyContent.querySelectorAll('.diff-view');
        diffViews.forEach(view => {
            let diff;
            if (isThread) {
                const oldTitle = decodeURIComponent(view.dataset.oldTitle);
                const newTitle = decodeURIComponent(view.dataset.newTitle);
                const oldContent = decodeURIComponent(view.dataset.oldContent);
                const newContent = decodeURIComponent(view.dataset.newContent);
                diff = Diff.createTwoFilesPatch("Old Thread", "New Thread", `${oldTitle}\n\n${oldContent}`, `${newTitle}\n\n${newContent}`);
            } else {
                const oldContent = decodeURIComponent(view.dataset.oldContent);
                const newContent = decodeURIComponent(view.dataset.newContent);
                diff = Diff.createTwoFilesPatch("Old Content", "New Content", oldContent, newContent);
            }
            const diffHtml = Diff2Html.html(diff, {
                drawFileList: false,
                matching: 'lines',
                outputFormat: 'side-by-side'
            });
            view.innerHTML = diffHtml;
        });
    }
// Link and Sharing

// Open unlike confirmation modal
function openUnlikeModal(element, type) {
    unlikeTargetElement = element;
    unlikeType = type;
    document.getElementById('unlikeModal').classList.remove('hidden');
}

// Close unlike confirmation modal
function closeUnlikeModal() {
    document.getElementById('unlikeModal').classList.add('hidden');
    unlikeTargetElement = null;
    unlikeType = null;
}

// Event listeners for unlike buttons (assuming same button toggles like/unlike)
document.querySelectorAll('.like-thread-btn').forEach(button => {
    button.addEventListener('click', function() {
        const threadId = this.dataset.threadId;
        const isLiked = this.classList.contains('liked'); // Check if already liked
        if (isLiked) {
            openUnlikeModal(this, 'thread');
        } else {
            fetch(`/threads/${threadId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                this.querySelector('.likes-count').textContent = data.likes_count;
                this.classList.add('liked'); // Mark as liked
            })
            .catch(error => console.error('Error liking thread:', error));
        }
    });
});

// Like buttons for threads
document.querySelectorAll('.like-thread-btn').forEach(button => {
    button.addEventListener('click', function() {
        const threadId = this.dataset.threadId;
        fetch(`/threads/${threadId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            this.querySelector('.likes-count').textContent = data.likes_count;
        })
        .catch(error => console.error('Error liking thread:', error));
    });
});

// Like buttons for posts
document.querySelectorAll('.like-post-btn').forEach(button => {
    button.addEventListener('click', function() {
        const postId = this.dataset.postId;
        fetch(`/posts/${postId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            this.querySelector('.likes-count').textContent = data.likes_count;
        })
        .catch(error => console.error('Error liking post:', error));
    });
});

// Share buttons for threads
document.querySelectorAll('.share-thread-btn').forEach(button => {
    button.addEventListener('click', function() {
        const url = this.dataset.threadUrl;
        if (navigator.share) {
            navigator.share({
                title: document.title,
                url: url
            }).then(() => console.log('Shared successfully'))
            .catch(error => console.error('Error sharing:', error));
        } else {
            alert('Sharing is not supported on this browser.');
        }
    });
});

    
});
