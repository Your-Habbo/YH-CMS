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
                                        <div class="h-32 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $thread->user->profile_banner ?? 'default_banner_url') }}');"></div>
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
                                            <p class="text-xxs text-center text-gray-700 truncate">{{ $thread->user->mot ?? 'No MOTD set.' }}</p>
                                        </div>

                                        <!-- User Stats -->
                                        <div class="text-xxs text-gray-600">
                                            <p><span class="font-semibold">Status:</span> {{ $thread->user->is_online ? 'Online' : 'Offline' }}</p>
                                            <p><span class="font-semibold">Posts:</span> {{ $thread->user->forumPosts_count ?? 0 }}</p>
                                            <p><span class="font-semibold">Points:</span> {{ $thread->user->contribution_points ?? 0 }}</p>
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
                                                {{ $thread->view_count ?? 0 }} views
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
                                        <!-- Thread Like Button -->
                                        <button class="like-thread-btn flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs hover:bg-blue-200 transition-colors"
                                                data-thread-id="{{ $thread->id }}" data-liked="{{ $userHasLikedThread }}">
                                            <i class="fas fa-heart mr-1"></i>
                                            <span class="likes-count">{{ $thread->likes_count }}</span>
                                        </button>

                                        <button class="share-thread-btn flex items-center px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs hover:bg-gray-200 transition-colors" data-thread-url="{{ route('forum.show', $thread->slug) }}">
                                            <i class="fas fa-share mr-1"></i> Share
                                        </button>
                                        @if(auth()->id() === $thread->user_id)
                                        <button class="edit-thread-btn flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs hover:bg-yellow-200 transition-colors" data-thread-id="{{ $thread->id }}">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        @endif
                                        <button class="view-thread-history-btn flex items-center px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs hover:bg-gray-200 transition-colors" data-thread-id="{{ $thread->id }}">
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
                                    <!-- User Banner -->
                                    <div class="h-24 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $post->user->profile_banner ?? 'default_banner_url') }}');"></div>
                                    <!-- User Info -->
                                    <div class="p-2">
                                        <div class="flex items-center mb-2">
                                            <img src="{{ getAvatarUrl($post->user->avatar_config ?? 'default_avatar_config') }}" alt="{{ $post->user->name ?? 'Default Avatar' }}'s Avatar" class="w-10 h-10 rounded-full mr-2">
                                            <div>
                                                <h5 class="text-xs font-bold truncate">{{ $post->user->name }}</h5>
                                                <p class="text-xxs text-gray-600 truncate">{{ $post->user->roles->pluck('name')->implode(', ') ?? 'Member' }}</p>
                                            </div>
                                        </div>
                                        <div class="text-xxs text-gray-600">
                                            <p class="mb-1"><span class="font-semibold">MOT:</span> {{ $post->user->mot ?? 'No MOTD set.' }}</p>
                                            <p class="mb-1"><span class="font-semibold">Posts:</span> {{ $post->user->forumPosts_count ?? 0 }}</p>
                                            <p class="mb-1"><span class="font-semibold">Points:</span> {{ $post->user->contribution_points ?? 0 }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Post Content -->
                            <div class="post-content flex-grow flex flex-col justify-between">
                                <p class="mb-4 text-xs sm:text-sm text-gray-800">{{ $post->content }}</p>
                                
                                <!-- User Signature -->
                                <p class="mt-4 text-xs text-gray-600 border-t pt-2">{{ $post->user->forum_signature ?? 'No signature.' }}</p>

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
                                        <!-- Post Like Button (within the loop that renders posts) -->
                                        <button class="like-post-btn flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200 transition-colors"
                                                data-post-id="{{ $post->id }}" data-liked="{{ $post->userHasLiked }}">
                                            <i class="fas fa-thumbs-up mr-1"></i>
                                            <span class="likes-count">{{ $post->likes_count }}</span>
                                        </button>
                                        
                                        @if($post->user_id === auth()->id())
                                        <button class="edit-post-btn flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full hover:bg-yellow-200 transition-colors" data-post-id="{{ $post->id }}">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        @endif
                                        @if($post->is_edited)
                                        <button class="view-history-btn flex items-center px-2 py-1 bg-gray-100 text-gray-800 rounded-full hover:bg-gray-200 transition-colors" data-post-id="{{ $post->id }}">
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
                    <form action="{{ route('forum.posts.store', $thread->id) }}" method="POST">
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





