
@section('title', 'YourHabbo Homepage')


@php
function getAvatarUrl($avatarConfig) {
    $baseUrl = url('/habbo-imaging/avatarimage');
    $params = [
        'figure' => $avatarConfig,
        'size' => 'm',
        'head_direction' => 2,
        'headonly' => 1,
    ];
    return $baseUrl . '?' . http_build_query($params);
}
@endphp


    <div class="w-full max-w-[1250px] flex flex-col pb-20">
            @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
            @endcomponent
            <div class="w-full flex flex-col lg:flex-row">
                <!-- Main Section - full width -->
                <div class="w-full">
                    <section class="card threads">
                        <div class="card-header blue">
                            <h3 class="text-base sm:text-lg">
                                <i class="fas fa-comments mr-2"></i>Forum Thread
                            </h3>
                        </div>

                        <div class="card-content p-3 sm:p-5">
                        <div class="thread-content mb-3 sm:mb-5 bg-white p-4 rounded-lg shadow-sm">
    <div class="flex flex-col sm:flex-row">
        <!-- User Card -->
        <div class="w-full sm:w-1/4 mb-3 sm:mb-0 sm:mr-4">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- User Banner with overlapping avatar and nearby username -->
                <div class="relative">
                    <div class="h-32 bg-cover bg-center" style="background-image: url('{{ $thread->user->banner_url ?? 'default_banner_url' }}');"></div>
                    <div class="absolute bottom-0 left-0 w-full p-1 bg-gradient-to-t from-black to-transparent">
                        <div class="flex items-end">
                            <img src="{{ getAvatarUrl($thread->user->avatar_config ?? 'default_avatar_config') }}" alt="{{ $thread->user->name ?? 'Default Avatar' }}'s Avatar" class="w-12 h-12 rounded-full border-2 border-white mr-2">
                            <div class="text-white">
                                <h4 class="text-xs font-bold truncate">{{ $thread->user->name }}</h4>
                                <p class="text-xxs truncate">{{ $thread->user->roles->pluck('name')->map(function($role) { return ucwords($role); })->implode(', ') ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Info -->
                <div class="p-2">
                    <!-- User Badge and MOTD -->
                    <div class="bg-gray-100 rounded p-1 mb-2">
                        <img src="{{ $thread->user->badge_url ?? 'default_badge_url' }}" alt="User Badge" class="w-6 h-6 mx-auto mb-1">
                        <p class="text-xxs text-center text-gray-700 truncate">{{ $thread->user->motd ?? 'No message of the day.' }}</p>
                    </div>

                    <!-- User Stats -->
                    <div class="text-xxs text-gray-600">
                        <p><span class="font-semibold">Status:</span> {{ $thread->user->is_online ? 'Online' : 'Offline' }}</p>
                        <p><span class="font-semibold">Posts:</span> {{ $thread->user->posts_count ?? 0 }}</p>
                        <p><span class="font-semibold">Points:</span> {{ $thread->user->points ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thread Content -->
        <div class="flex-grow flex flex-col">
            <div class="flex-grow">
                <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $thread->title }}</h2>
                <p class="text-sm text-gray-600 mb-4">{{ $thread->content }}</p>
            </div>

            <!-- Right Column for Metadata and Actions -->
            <div class="flex justify-between items-end">
                <div class="text-xs text-gray-500">
                    <div class="mb-2">
                        <span class="mr-3">
                            <i class="far fa-clock mr-1"></i>
                            Posted {{ $thread->created_at->diffForHumans() }}
                        </span>
                        @if($thread->is_edited)
                        <span class="mr-3">
                            <i class="fas fa-edit mr-1"></i>
                            Edited
                        </span>
                        @endif
                        <span>
                            <i class="far fa-eye mr-1"></i>
                            {{ $thread->views ?? 0 }} views
                        </span>
                    </div>

                    <div class="thread-tags mt-2">
                        @foreach($thread->tags as $tag)
                        <span class="inline-block bg-blue-100 text-blue-800 text-xxs px-2 py-1 rounded-full mr-2 mb-2">
                            {{ $tag->name }}
                        </span>
                        @endforeach
                    </div>
                </div>

                <div class="thread-actions flex flex-wrap items-center gap-2">
                    <button class="flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs hover:bg-red-200 transition-colors {{ $thread->userHasLiked() ? 'bg-red-200' : '' }}" data-thread-id="{{ $thread->id }}">
                        <i class="fas fa-heart mr-1"></i>
                        <span class="likes-count">{{ $thread->likes_count }}</span>
                    </button>
                    <button class="flex items-center px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs hover:bg-gray-200 transition-colors" data-thread-url="{{ route('forum.show', $thread->slug) }}">
                        <i class="fas fa-share mr-1"></i> Share
                    </button>
                    @if(auth()->id() === $thread->user_id)
                    <button class="flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs hover:bg-yellow-200 transition-colors" data-thread-id="{{ $thread->id }}">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                    @endif
                    <button class="flex items-center px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs hover:bg-gray-200 transition-colors" data-thread-id="{{ $thread->id }}">
                        <i class="fas fa-history mr-1"></i> History
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


                            <hr class="mb-3 sm:mb-5 border-t border-gray-300">

                            <h4 class="text-sm sm:text-base font-bold mb-2 sm:mb-3">Replies ({{ $thread->posts->count() }})</h4>

                            @foreach($thread->posts as $index => $post)
<div class="post-item p-2 sm:p-3 border-b border-gray-200 mb-2 sm:mb-3 rounded-lg shadow-sm 
    {{ $index % 2 == 0 ? 'bg-gradient-to-r from-gray-100 to-gray-50' : 'bg-gradient-to-r from-gray-50 to-gray-100' }}">
    <div class="flex flex-col sm:flex-row items-start">
        <!-- User Card (for replies) -->
        <div class="w-full sm:w-1/5 mb-3 sm:mb-0 sm:mr-4">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- User Info -->
                <div class="bg-white rounded-lg shadow-sm p-2">
                    <div class="flex items-center mb-2">
                        <img src="{{ getAvatarUrl($post->user->avatar_config ?? 'default_avatar_config') }}" alt="{{ $post->user->name ?? 'Default Avatar' }}'s Avatar" class="w-10 h-10 rounded-full mr-2">
                        <div>
                            <h5 class="text-xs font-bold truncate">{{ $post->user->name }}</h5>
                            <p class="text-xxs text-gray-600 truncate">{{ $post->user->roles->pluck('name')->implode(', ') ?? 'Member' }}</p>
                        </div>
                    </div>
                    <div class="text-xxs text-gray-600">
                        <p class="mb-1">
                            <span class="inline-block w-4 h-4 rounded-full mr-1 {{ $post->user->is_online ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                            {{ $post->user->roles->pluck('name')->map(function($role) { return ucwords($role); })->implode(', ') ?? 'Member' }}
                        </p>
                        <p class="mb-1"><span class="font-semibold">Posts:</span> {{ $thread->user->forum_post_count ?? 0 }}</p>
                        <p class="mb-1"><span class="font-semibold">Points:</span> {{ $thread->user->contribution_points ?? 0 }}</p>

                    </div>
                </div>
            </div>
        </div>

        <!-- Post Content -->
        <div class="post-content flex-grow flex flex-col justify-between">
            <p class="mb-4 text-xs sm:text-sm text-gray-800">{{ $post->content }}</p>
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center text-xxs sm:text-xs text-gray-600">
                <!-- Left Side: Posted Info -->
                <div class="mb-2 sm:mb-0">
                    Posted {{ $post->created_at->diffForHumans() }}
                    @if($post->is_edited)
                    <span class="ml-1 sm:ml-2 text-xxs text-gray-500">(Edited)</span>
                    @endif
                </div>

                <!-- Right Side: Actions -->
                <div class="flex flex-wrap gap-2">
                    <button class="flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200 transition-colors {{ $post->userHasLiked() ? 'bg-blue-200' : '' }}" data-post-id="{{ $post->id }}">
                        <i class="fas fa-thumbs-up mr-1"></i>
                        <span class="likes-count">{{ $post->likes_count }}</span>
                    </button>
                    @if($post->user_id === auth()->id())
                    <button class="flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full hover:bg-yellow-200 transition-colors" data-post-id="{{ $post->id }}">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                    @endif
                    @if($post->is_edited)
                    <button class="flex items-center px-2 py-1 bg-gray-100 text-gray-800 rounded-full hover:bg-gray-200 transition-colors" data-post-id="{{ $post->id }}">
                        <i class="fas fa-history mr-1"></i> History
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach


                        </div>

                        <div class="card-content p-3 sm:p-5 bg-gray-100 rounded-b-lg">
                            <h4 class="text-sm sm:text-base font-bold mb-2 sm:mb-3">Leave a Reply</h4>
                            <form action="{{ route('posts.store', $thread->id) }}" method="POST">
                                @csrf
                                <div class="mb-2 sm:mb-3">
                                    <textarea name="content" id="content" required rows="4" class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-xs" placeholder="Type your reply here..."></textarea>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors text-sm">Post Reply</button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>


<!-- Unlike Confirmation Modal -->
<div id="unlikeModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-base leading-6 font-medium text-gray-900" id="modal-title">
                    Confirm Unlike
                </h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">Are you sure you want to unlike this post/thread?</p>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="confirmUnlikeBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-xs">
                    Unlike
                </button>
                <button type="button" id="cancelUnlikeBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-xs">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Edit Post Modal -->
<div id="editPostModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                    Edit Post
                </h3>
                <div class="mt-2">
                    <textarea id="editPostContent" class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" rows="4"></textarea>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="saveEditBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Save
                </button>
                <button type="button" id="cancelEditBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Thread Modal -->
<div id="editThreadModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                    Edit Thread
                </h3>
                <div class="mt-2">
                    <input type="text" id="editThreadTitle" class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm mb-2" placeholder="Thread Title">
                    <textarea id="editThreadContent" class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" rows="4" placeholder="Thread Content"></textarea>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="saveThreadEditBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Save
                </button>
                <button type="button" id="cancelThreadEditBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit History Modal -->
<div id="editHistoryModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="history-modal-title">
                    Edit History
                </h3>
                <div class="mt-2" id="editHistoryContent">
                    <!-- Edit history content will be dynamically inserted here -->
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="closeHistoryBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Unlike Confirmation Modal -->
<div id="unlikeModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                    Confirm Unlike
                </h3>
                <div class="mt-2">
                    <p>Are you sure you want to unlike this post/thread?</p>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="confirmUnlikeBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Unlike
                </button>
                <button type="button" id="cancelUnlikeBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/diff/4.0.1/diff.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/diff2html/2.12.2/diff2html.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/diff2html/2.12.2/diff2html.min.css" />
<link rel="stylesheet" href="{{ asset('assets/js/forum-post.js') }}" />
<script>

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


    
});

    </script>
   <script>

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



</script>
@endsection