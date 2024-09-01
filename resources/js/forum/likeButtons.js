import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

export function initializeLikeButtons() {
    //console.log('Initializing like buttons...');
    const cooldownTime = 3000; // Cooldown time in milliseconds (3 seconds)
    const buttonCooldowns = new Map();

    document.querySelectorAll('.like-post-btn, .like-thread-btn').forEach(button => {
        const liked = button.getAttribute('data-liked') === 'true';
        if (liked) {
            button.classList.add('bg-red-500', 'text-white');
            button.classList.remove('bg-blue-100', 'text-blue-800');
        } else {
            button.classList.add('bg-blue-100', 'text-blue-800');
            button.classList.remove('bg-red-500', 'text-white');
        }

        button.addEventListener('click', function() {
            const now = Date.now();
            const lastClicked = buttonCooldowns.get(this) || 0;

            if (now - lastClicked < cooldownTime) {
                toastr.warning('Please wait a moment before trying again.');
                return;
            }

            buttonCooldowns.set(this, now); // Update the last clicked time

            const postId = this.getAttribute('data-post-id');
            const threadId = this.getAttribute('data-thread-id');
            const liked = this.getAttribute('data-liked') === 'true';
            const url = postId
                ? (liked ? `/forum/posts/${postId}/unlike` : `/forum/posts/${postId}/like`)
                : (liked ? `/forum/${threadId}/unlike` : `/forum/${threadId}/like`);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                this.querySelector('.likes-count').innerText = data.likes_count;
                this.setAttribute('data-liked', data.liked);

                if (data.liked) {
                    this.classList.add('bg-red-500', 'text-white');
                    this.classList.remove('bg-blue-100', 'text-blue-800');
                } else {
                    this.classList.add('bg-blue-100', 'text-blue-800');
                    this.classList.remove('bg-red-500', 'text-white');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred while processing your request.');
            });
        });
    });
}
