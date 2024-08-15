

<main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <div class="flex flex-col lg:flex-row">
            <!-- Main content area (70%) -->
            <div class="w-full lg:w-[70%] lg:pr-6">
                <div class="card mb-6">
                    <div class="card-header blue flex justify-between items-center">
                        <h1 class="font-bold">Latest News</h1>
                        <form action="{{ route('news.search') }}" method="GET" class="flex">
                            <input type="text" name="query" placeholder="Search news..." class="px-3 py-1 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500 text-sm" aria-label="Search news">
                            <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    <div class="card-content p-4">
                        @forelse ($news as $article)
                            <article class="mb-6 pb-6 border-b border-gray-200 last:border-b-0 last:pb-0">
                                <div class="flex flex-col sm:flex-row">
                                    <div class="sm:w-1/4 mb-4 sm:mb-0 sm:mr-4">
                                        @if($article->image)
                                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-32 object-cover rounded">
                                        @else
                                            <div class="w-full h-32 bg-gray-200 flex items-center justify-center rounded">
                                                <i class="fas fa-newspaper text-4xl text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="sm:w-3/4">
                                        <h2 class="text-xl font-semibold mb-2 hover:text-blue-600 transition duration-300">
                                            <a href="{{ route('news.show', $article->slug) }}">{{ $article->title }}</a>
                                        </h2>
                                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($article->content, 150) }}</p>
                                        <div class="flex items-center justify-between text-sm text-gray-500">
                                            <span><i class="far fa-calendar-alt mr-2"></i>{{ $article->created_at->format('M d, Y') }}</span>
                                            <span><i class="far fa-user mr-2"></i>{{ $article->author->name ?? 'Unknown Author' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-600">No news articles found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="mt-6">
                    {{ $news->links() }}
                </div>
            </div>

            <!-- Sidebar (30%) -->
            <div class="w-full lg:w-[30%]">
                <!-- Recent Articles -->
                <div class="card mb-6">
                    <div class="card-header pastel-green">
                        <h4 class="font-bold">Recent Articles</h4>
                    </div>
                    <div class="card-content p-4">
                        <ul class="space-y-2">
                            @foreach($news->take(5) as $recentArticle)
                                <li>
                                    <a href="{{ route('news.show', $recentArticle->slug) }}" class="text-blue-600 hover:underline">
                                        {{ Str::limit($recentArticle->title, 50) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Categories or Tags -->
                <div class="card mb-6">
                    <div class="card-header pastel-orange">
                        <h4 class="font-bold">Categories</h4>
                    </div>
                    <div class="card-content p-4">
                        <!-- Add your categories or tags here -->
                        <p class="text-gray-600">Categories coming soon...</p>
                    </div>
                </div>

                <!-- Archive -->
                <div class="card">
                    <div class="card-header pastel-blue">
                        <h4 class="font-bold">Archive</h4>
                    </div>
                    <div class="card-content p-4">
                        <!-- Add your archive links here -->
                        <p class="text-gray-600">Archive coming soon...</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
