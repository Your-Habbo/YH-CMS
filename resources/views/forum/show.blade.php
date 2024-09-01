@pjax('layouts.app')
@section('title', 'YourHabbo Homepage')


@php
if (!function_exists('getAvatarUrl')) {
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
                    <h3 class="text-base sm:text-m">
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
                                    <p class="text-sm text-gray-600 mb-4">{!! $thread->content !!}</p>
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
                                            data-thread-id="{{ $thread->id }}" data-liked="{{ $userHasLikedThread ? 'true' : 'false' }}">
                                            <i class="fas fa-heart mr-1"></i>
                                            <span class="likes-count">{{ $thread->likes_count }}</span>
                                        </button>

                                        <button class="share-thread-btn flex items-center px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs hover:bg-gray-200 transition-colors" data-thread-url="{{ route('forum.show', $thread->slug) }}">
                                            <i class="fas fa-share mr-1"></i> Share
                                        </button>
                                        @if(auth()->id() === $thread->user_id)
                                        <a href="{{ route('forum.threads.edit', $thread->id) }}" class="edit-thread-btn flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs hover:bg-yellow-200 transition-colors">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        @endif
                                        @if($thread->is_edited)
                                        <button class="view-history-btn flex items-center px-2 py-1 bg-gray-100 text-gray-800 rounded-full hover:bg-gray-200 transition-colors"
                                        data-toggle="collapse" data-target="#history-thread">
                                        <i class="fas fa-history mr-1"></i> History
                                        </button>
                                        @endif
                                        @if(Auth::user() && Auth::user()->can('manage forum'))
                                            <button id="toggleStickyBtn" class="sticky-thread-btn px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors text-xs" data-thread-id="{{ $thread->id }}">
                                                <i class="fas fa-thumbtack mr-1"></i> 
                                                <span id="stickyBtnText">{{ $thread->is_sticky ? 'Unsticky' : 'Sticky' }}</span>
                                            </button>
                                        @endif
                                        @auth
                                            @if(Auth::id() === $thread->user_id || Auth::user()->can('delete forum threads'))
                                                <button class="delete-thread-btn px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors text-xs" data-thread-id="{{ $thread->id }}">
                                                    <i class="fas fa-trash mr-1"></i> Delete Thread
                                                </button>
                                            @endif
                                        @endauth

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($thread->is_edited)
                        <!-- Collapsible History Section -->
                        <div id="history-thread" class="collapse mt-3 bg-gray-100 p-3 rounded hidden">
                            @foreach($thread->editHistory as $history)
                            <div class="mb-2">
                                <strong>Edited by:</strong> {{ $history->user->name }} <br>
                                <strong>Old Title:</strong> {{ $history->old_title ?? 'N/A' }} <br>
                                <strong>New Title:</strong> {{ $history->new_title ?? 'N/A' }} <br>
                                <strong>Old Content:</strong> {!! nl2br(e($history->old_content)) !!} <br>
                                <strong>New Content:</strong> {!! nl2br(e($history->new_content)) !!} <br>
                                <strong>Edited at:</strong> {{ $history->created_at->format('Y-m-d H:i:s') }}
                            </div>
                            <hr class="border-gray-300 my-2">
                        @endforeach
                        </div>


                    @endif

                    <div class="space-y-6">
                        @foreach($paginatedPosts as $index => $post)
                        
                        <div class="post-item mb-6 rounded-lg shadow-sm overflow-hidden
                            {{ $index % 2 == 0 ? 'bg-gradient-to-r from-gray-100 to-gray-50' : 'bg-gradient-to-r from-gray-50 to-gray-100' }}">
                            <div class="flex flex-col sm:flex-row">
                                <!-- User Card - Keep as is -->
                                <div class="w-full sm:w-48 bg-white flex flex-col overflow-hidden">
                                    <div class="h-24 bg-cover bg-center relative" 
                                         style="background-image: url('{{ asset('storage/' . $post->user->profile_banner ?? 'default_banner_url') }}');">
                                        <div class="absolute bottom-0 left-0 w-full p-2 bg-gradient-to-t from-black to-transparent">
                                            <div class="flex items-end">
                                                <img src="{{ getAvatarUrl($post->user->avatar_config ?? 'default_avatar_config') }}" 
                                                     alt="{{ $post->user->name }}'s Avatar" 
                                                     class="w-10 h-10 rounded-full border-2 border-white mr-2" loading="lazy">
                                                <div class="text-white">
                                                    <h4 class="text-xs font-bold truncate">{{ $post->user->name }}</h4>
                                                    <p class="text-xxs truncate">{{ $post->user->roles->pluck('name')->implode(', ') ?? 'Member' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-2">
                                        <div class="text-xxs text-gray-600">
                                            <p class="mb-1"><span class="font-semibold">MOT:</span> <span class="truncate block">{{ $post->user->mot ?? 'No MOTD set.' }}</span></p>
                                            <p class="mb-1"><span class="font-semibold">Posts:</span> {{ $post->user->forumPosts_count ?? 0 }}</p>
                                            <p class="mb-1"><span class="font-semibold">Points:</span> {{ $post->user->contribution_points ?? 0 }}</p>
                                        </div>
                                        <div class="mt-2 text-xxs">
                                            <p class="{{ $post->user->is_online ? 'text-green-500' : 'text-red-500' }}">
                                                {{ $post->user->is_online ? 'Online' : 'Offline' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                        
                                <!-- Post Content - With max-width -->
                                <div class="flex-grow p-4 sm:p-6 flex flex-col justify-between">
                                    <div class="max-w-[800px]"> <!-- Added max-width here -->
                                        <div class="prose prose-sm max-w-none mb-4">
                                            {!! clean($post->content) !!}
                                        </div>
                                        
                                        @if($post->user->forum_signature)
                                        <div class="prose">
                                            <div class="mt-4 text-xs text-gray-600 border-t pt-2">
                                                {!! clean($post->user->forum_signature) !!}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                        
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center text-xs text-gray-600 mt-4">
                                        <div>
                                            Posted {{ $post->created_at->diffForHumans() }}
                                            @if($post->is_edited)
                                                <span class="ml-2 text-xs text-gray-500">(Edited)</span>
                                            @endif
                                        </div>
                        
                                        <div class="flex flex-wrap gap-2 mt-2 sm:mt-0">
                                            <button class="like-post-btn flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200 transition-colors"
                                                    data-post-id="{{ $post->id }}" data-liked="{{ $post->userHasLiked ? 'true' : 'false' }}">
                                                <i class="fas fa-thumbs-up mr-1"></i>
                                                <span class="likes-count">{{ $post->likes_count }}</span>
                                            </button>
                                            
                                            @if($post->user_id === auth()->id())
                                            <button class="edit-post-btn flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full hover:bg-yellow-200 transition-colors"
                                                    data-post-id="{{ $post->id }}"
                                                    data-post-content="{{ $post->content }}">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </button>
                                            @endif
                                            @if(auth()->id() === $thread->user_id && !$thread->is_resolved)
                                            <form action="{{ route('forum.posts.markSolution', [$thread->id, $post->id]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Mark as Solution</button>
                                            </form>
                                            @endif
                                            @if($post->is_edited)
                                            <button class="view-history-btn flex items-center px-2 py-1 bg-gray-100 text-gray-800 rounded-full hover:bg-gray-200 transition-colors"
                                                    data-toggle="collapse" data-target="#history-{{ $post->id }}">
                                                <i class="fas fa-history mr-1"></i> History
                                            </button>
                                            @endif

                                            @auth
                                            
                                            @if(Auth::id() === $post->user_id || Auth::user()->can('delete forum posts'))
                                                @if(!$post->deleted_at)
                                                    <button class="delete-post-btn px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors text-xs" data-post-id="{{ $post->id }}">
                                                        <i class="fas fa-trash mr-1"></i> Delete Post
                                                    </button>
                                                @else
                                                    <span class="text-gray-500 text-xs italic">Post deleted</span>
                                                @endif
                                            @endif
                                        @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Post History Section -->
                        @if($post->is_edited)
                        <!-- Collapsible History Section -->
                        <div id="history-{{ $post->id }}" class="collapse mt-3 bg-gray-100 p-3 rounded hidden">
                            @foreach($post->editHistory as $history)
                                <div class="mb-2">
                                    <strong>Edited by:</strong> {{ $history->user->name }} <br>
                                    <strong>Old Content:</strong> {!! nl2br(e($history->old_content)) !!} <br>
                                    <strong>New Content:</strong> {!! nl2br(e($history->new_content)) !!} <br>
                                    <strong>Edited at:</strong> {{ $history->created_at->format('Y-m-d H:i:s') }}
                                </div>
                                <hr class="border-gray-300 my-2">
                            @endforeach
                        </div>
                        @endif
                        @endforeach
                    </div>
                    <div class="pt-5">
                        {{ $paginatedPosts->links() }} <!-- Pagination -->
                    </div>
                    @auth
                    <!-- Reply Form -->
                    <div class="bg-gray-100 rounded-lg p-4 mt-2">
                        <h3 class="text-lg font-bold mb-3">Leave a Reply</h3>
                        <form action="{{ route('forum.posts.store', $thread->id) }}" method="POST" id="reply-form">
                            @csrf
                            <div class="mb-3">
                                <textarea name="content" id="content" required rows="4" 
                                          class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                          placeholder="Type your reply here..."></textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" id="submit-button" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors text-sm">
                                    Post Reply
                                </button>
                            </div>
                        </form>
                    </div>
                    @else
                        
                    @endauth
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Add necessary JavaScript for collapsible sections -->


<!-- Edit Post Modal -->
<div id="editPostModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
      
      <!-- Modal Content -->
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Post</h3>
          <div class="mt-2">
            <textarea id="editPostContent" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button id="confirmEditPostButton" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
          <button type="button" id="cancelEditPostButton" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
        </div>
      </div>
    </div>
  </div>
  
  @include('forum.components.confirmation')
  

  @section('scripts')

@endsection