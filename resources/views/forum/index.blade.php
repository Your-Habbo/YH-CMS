

@section('content')
@php
function getAvatarUrl($avatarConfig) {
    $baseUrl = url('/habbo-imaging/avatarimage');
    $params = [
        'figure' => $avatarConfig,
        'size' => 's',
        'head_direction' => 2,
        'direction' => 2,
    ];
    return $baseUrl . '?' . http_build_query($params);
}
@endphp

    <div class="w-full max-w-[1250px] flex flex-col">
        @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
        @endcomponent
        <!-- Categories Section -->
        @unless($hideCategories)
        <div class="categories-section grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 sm:gap-4 mb-6">
            @foreach($categories as $category)
                <a href="{{ route('forum.category', $category->slug) }}" class="category-card flex flex-col items-center bg-white p-2 sm:p-3 rounded-lg shadow hover:shadow-lg transition transform hover:scale-105">
                    <img src="" alt="{{ $category->name }} image" class="w-full h-16 sm:h-20 object-cover mb-2 rounded">
                    <h4 class="text-xs sm:text-sm font-bold text-center">{{ $category->name }}</h4>
                </a>
            @endforeach
            @isset($category)
        <a href="{{ route('forum.index') }}" class="back-button bg-blue-500 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-600 transition">
            &larr; Back to Forum
        </a>
    @endisset

        </div>
        @endunless
        <div class="w-full flex flex-col lg:flex-row">
            <!-- Main Section - full width on mobile, 70% on larger screens -->
            <div class="w-full lg:w-[70%] lg:pr-6 mb-6 lg:mb-0">
                <div class="card">
                    <div class="card-header blue flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <h3 class="flex items-center mb-2 sm:mb-0 text-base sm:text-lg">
                            <i class="fas fa-comments mr-2"></i>
                            Discussion
                        </h3>
                    </div>
                    <div class="card-content">
                        <div class="flex flex-col sm:flex-row flex-wrap items-start sm:items-center justify-between mb-4">
                            <div class="filter-buttons flex flex-wrap space-x-2 sm:space-x-4 mb-4 sm:mb-0">
                                <button class="filter-button text-blue-500 hover:text-blue-700 focus:outline-none text-xs" data-filter="newest">Newest</button>
                                <button class="filter-button text-blue-500 hover:text-blue-700 focus:outline-none text-xs" data-filter="hottest">Hottest</button>
                                <button class="filter-button text-blue-500 hover:text-blue-700 focus:outline-none text-xs" data-filter="solved">Solved</button>
                                <button class="filter-button text-blue-500 hover:text-blue-700 focus:outline-none text-xs" data-filter="unsolved">Unsolved</button>
                            </div>
                            <div class="flex flex-col sm:flex-row items-start sm:items-center w-full sm:w-auto">
                                <div class="search-bar mb-2 sm:mb-0 sm:mr-4 flex w-full sm:w-auto">
                                    @auth
                                        <form action="{{ route('forum.search') }}" method="GET" class="flex w-full">
                                            <input type="text" name="query" placeholder="Search threads..." class="px-3 py-2 rounded-l border focus:outline-none text-xs flex-grow" aria-label="Search threads">
                                            <button type="submit" class="px-4 py-2 font-medium bg-blue-50 hover:bg-blue-100 hover:text-blue-600 text-blue-500 rounded-r-lg text-xs" aria-label="Submit search">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </form>
                                    @else
                                        <div class="flex items-center w-full">
                                            <span class="text-gray-500 mr-2 text-xs">Sign in to search</span>
                                            <a href="{{ route('login') }}" class="px-4 py-2 font-medium bg-blue-50 hover:bg-blue-100 hover:text-blue-600 text-blue-500 rounded-lg text-xs">
                                                Sign In
                                            </a>
                                        </div>
                                    @endauth
                                </div>

                                @auth
                                    <a href="{{ route('forum.create') }}" class="px-4 py-2 font-medium bg-blue-50 hover:bg-blue-100 hover:text-blue-600 text-blue-500 rounded-lg text-xs w-full sm:w-auto text-center">
                                        Add Topic
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="px-4 py-2 font-medium bg-blue-50 hover:bg-blue-100 hover:text-blue-600 text-blue-500 rounded-lg text-xs w-full sm:w-auto text-center">
                                        Login to Add Topic
                                    </a>
                                @endauth
                            </div>
                        </div>
                        <div class="forum-threads">
                            @forelse($threads as $thread)
                                <div class="thread-item p-4 border-b border-gray-200 flex flex-col sm:flex-row hover:bg-gray-50 transition duration-300 rounded {{ $thread->is_sticky ? 'sticky bg-yellow-100' : '' }}">
                                    <div class="avatar flex-shrink-0 mb-2 sm:mb-0">
                                        <img src="{{ getAvatarUrl($thread->user->avatar_config) }}" alt="{{ $thread->user->name }}'s Avatar" class="w-10 h-10 rounded-full">
                                    </div>
                                    <div class="thread-details sm:ml-4 flex-grow">
                                        <div class="flex flex-wrap items-center mb-1">
                                            @if($thread->is_sticky)
                                                <span class="bg-yellow-500 text-white text-xxs px-2 py-1 rounded mr-2 mb-1">Sticky</span>
                                            @endif
                                            <h3 class="text-sm sm:text-base font-bold hover:text-blue-500 transition duration-300">
                                                <a href="{{ route('forum.show', $thread->slug) }}">{{ $thread->title }}</a>
                                            </h3>
                                        </div>
                                        <p class="text-xxs sm:text-xs text-gray-600">Posted by <span class="font-semibold">{{ $thread->user->name }}</span> in <a href="{{ route('forum.category', $thread->category->slug) }}" class="font-semibold text-blue-500 hover:underline">{{ $thread->category->name }}</a></p>
                                        <p class="text-xxs sm:text-xs text-gray-600 mt-2">{{ Str::limit($thread->content, 80) }}</p>
                                        <div class="thread-meta mt-2 flex flex-wrap items-center">
                                            <span class="text-xxs sm:text-xs text-gray-600 mr-4 mb-1"><i class="fas fa-thumbs-up"></i> {{ $thread->upvotes }}</span>
                                            <span class="text-xxs sm:text-xs text-gray-600 mr-4 mb-1"><i class="fas fa-comment"></i> {{ $thread->posts_count }}</span>
                                            <span class="text-xxs sm:text-xs text-gray-600 mr-4 mb-1"><i class="fas fa-clock"></i> {{ $thread->created_at->diffForHumans() }}</span>
                                            @if($thread->requires_solution)
                                                @if($thread->is_resolved)
                                                    <span class="bg-green-500 text-white text-xxs px-2 py-1 rounded mb-1">Resolved</span>
                                                @else
                                                    <span class="bg-yellow-500 text-white text-xxs px-2 py-1 rounded mb-1">Unresolved</span>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="thread-tags mt-2 flex flex-wrap">
                                            @foreach($thread->tags as $tag)
                                                <a href="{{ route('forum.tag', $tag->slug) }}" class="tag text-white text-xxs px-2 py-1 rounded mr-2 mb-1 hover:opacity-80 transition duration-300" style="background-color: {{ $tag->color }};">
                                                    {{ $tag->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-gray-500 text-sm">
                                    No threads found. Be the first to start a discussion!
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <!-- Pagination -->
                    <div class="pagination flex justify-center mt-6 p-5">
                        {{ $threads->links() }}
                    </div>
                </div>
            </div>

            <!-- Right Side Section - full width on mobile, 30% on larger screens -->
            <div class="w-full lg:w-[30%]">
                <!-- Tag Categories -->
                <div class="card mb-4">
                    <div class="card-header pastel-mint">
                        <h4 class="text-sm"><i class="fas fa-tags mr-2"></i>Popular Tags</h4>
                    </div>
                    <div class="card-content p-4">
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-2 gap-4">
                            @foreach($tags as $tag)
                                <a href="{{ route('forum.tag', $tag->slug) }}" class="tag-category hover:opacity-80 transition duration-300 w-full h-16 sm:h-20 rounded-lg flex flex-col items-center justify-center text-white text-center" style="background-color: {{ $tag->color }};">
                                    <i class="fas fa-tag text-base sm:text-lg mb-1"></i>
                                    <span class="text-xxs">{{ $tag->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card mb-4">
                    <div class="card-header pastel-green">
                        <h4 class="text-sm"><i class="fas fa-clock mr-2"></i>Recent Activity</h4>
                    </div>
                    <div class="card-content p-4 overflow-x-auto">
                        <div class="activity-table">
                            <div class="activity-header grid grid-cols-3 gap-2 font-semibold text-xs mb-2">
                                <div class="activity-col">Habbo Name</div>
                                <div class="activity-col">Action</div>
                                <div class="activity-col">Time</div>
                            </div>
                            @foreach($recentActivities as $activity)
                                <div class="activity-row grid grid-cols-3 gap-2 text-xxs sm:text-xs py-2 border-b border-gray-200">
                                    <div class="activity-col">{{ $activity->user->name }}</div>
                                    <div class="activity-col">{{ $activity->action }}</div>
                                    <div class="activity-col">{{ $activity->created_at->diffForHumans() }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Top Contributors -->
                <div class="card mb-4">
                    <div class="card-header pastel-orange">
                        <h4 class="text-sm"><i class="fas fa-trophy mr-2"></i>Top Contributors</h4>
                    </div>
                    <div class="card-content p-4 overflow-x-auto">
                        <div class="contributors-table">
                            <div class="contributor-header grid grid-cols-3 gap-2 font-semibold text-xs mb-2">
                                <div class="contributor-col">Rank</div>
                                <div class="contributor-col">User</div>
                                <div class="contributor-col">Points</div>
                            </div>
                            @foreach($topContributors as $index => $contributor)
                                <div class="contributor-row grid grid-cols-3 gap-2 text-xxs sm:text-xs py-2 border-b border-gray-200">
                                    <div class="contributor-col">{{ $index + 1 }}</div>
                                    <div class="contributor-col">{{ $contributor->name }}</div>
                                    <div class="contributor-col">{{ $contributor->contribution_points }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

