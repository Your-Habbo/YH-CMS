

@section('title', 'YourHabbo Homepage')


<div class="max-w-[1250px] mx-auto mt-[-20px]">
    <div class="flex flex-col lg:flex-row lg:space-x-6">
        <!-- Main content area (70%) -->
        <div class="w-full lg:w-[70%] space-y-6">
            <!-- DJ Card -->
            <div class="card bg-white rounded-lg shadow-md flex items-center">
                <img src="https://www.habbo.es/habbo-imaging/avatarimage?user=noodlesoup&direction=3&head_direction=3&action=&gesture=sml&headonly=1" alt="DJ Avatar" class="dj-avatar w-16 h-16 rounded-full mr-4">
                <div class="dj-content">
                    <strong>[DJ Name] Says:</strong> Hello and welcome 
                </div>
            </div>



            <div class="card overflow-hidden">
    <div class="card-header pastel-mint px-6 py-4">
        <h2 class="font-bold text-white">Featured News</h2>
    </div>
    <div class="card-content p-4">
        <div x-data="carousel()" x-init="startAutoRotation()">
            <div class="relative overflow-hidden rounded-lg" style="padding-bottom: 41.67%;">
                @foreach($featuredNews as $index => $article)
                    <a href="{{ route('news.show', $article->slug) }}" class="absolute inset-0 w-full h-full group" x-show.immediate="activeSlide === {{ $index }}" x-cloak>
                        @if($article->image)
                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-newspaper text-4xl text-gray-400"></i>
                            </div>
                        @endif
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent text-white p-4">
                            <div class="flex justify-between items-end">
                                <div class="flex-grow">
                                    <h3 class="text-xl font-bold mb-2 group-hover:underline">{{ $article->title }}</h3>
                                    <p class="text-sm line-clamp-2 mb-2">{{ Str::limit($article->content, 100) }}</p>
                                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full text-sm transition-colors duration-200">
                                        Read More
                                    </button>
                                </div>
                                <div class="flex space-x-2 ml-4">
                                    @foreach($featuredNews as $i => $item)
                                        <button @click.stop="activeSlide = {{ $i }}" 
                                                class="w-6 h-6 rounded-full border-2 border-white flex items-center justify-center text-xs focus:outline-none transition-colors duration-200"
                                                :class="{ 'bg-white text-blue-600': activeSlide === {{ $i }}, 'bg-transparent text-white hover:bg-white/50': activeSlide !== {{ $i }} }">
                                            {{ $i + 1 }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>



            <!-- Welcome Card -->
            <div class="card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="card-header pastel-alice-blue bg-blue-100 px-6 py-4 border-b border-blue-200">
                    <h2 >WELCOME TO [Site Name]</h2>
                </div>
                <div class="card-content p-6">
                    This is where you can add the main content of the card. It will be styled within the card body.
                </div>
            </div>



            <!-- You can add more sections here if needed -->
        </div>

        <!-- Sidebar (30%) -->
        <div class="w-full lg:w-[30%] space-y-6">
        <!-- Latest News Sidebar -->
        <div class="card">
            <div class="card-header pastel-pale-pink px-4 py-3">
                <h2 class="text-white">Latest News</h2>
            </div>
            <div class="card-content p-4">
                <ul class="space-y-4">
                    @foreach($latestNews->take(3) as $news)
                        <li class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                            <a href="{{ route('news.show', $news->slug) }}" class="group block hover:bg-gray-50 transition duration-150 ease-in-out">
                                <div class="flex items-center">
                                    @if($news->image)
                                        <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="w-16 h-16 object-cover rounded-md mr-3">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-md mr-3 flex items-center justify-center">
                                            <i class="fas fa-newspaper text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h5 class="text-sm font-medium text-gray-900 truncate group-hover:text-blue-600">
                                            {{ $news->title }}
                                        </h5>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $news->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-4 text-center">
                    <a href="{{ route('news.index') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-full transition duration-150 ease-in-out">
                        View All News
                    </a>
                </div>
            </div>
        </div>

            <!-- Upcoming Events -->
            <div class="card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="card-header pastel-red px-6 py-4">
                    <h2 class="text-white">Upcoming Events</h2>
                </div>
                <div class="card-content p-4">
                    <ul class="space-y-4">
                        @foreach($upcomingEvents as $event)
                            <li>
                                <h5 class="font-semibold">{{ $event['name'] }}</h5>
                                <p class="text-sm text-gray-600">{{ $event['date'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                    <a href="#" class="block mt-4 text-center bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition duration-300">View All Events</a>
                </div>
            </div>

            <!-- Discord Widget -->
            <div class="card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="card-header pastel-light-purple px-6 py-4">
                    <h4 class="font-bold text-white">Join Our Discord</h4>
                </div>
                <div class="card-content p-4">
                    <div class="bg-gray-100 p-4 rounded-lg text-center">
                        <p class="mb-4">Connect with other Habbo fans!</p>
                        <a href="#" class="inline-block bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-600 transition duration-300">
                            <i class="fab fa-discord mr-2"></i>Join Discord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>



@section('scripts')
<style>
[x-cloak] { display: none !important; }
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
<script>
function carousel() {
    return {
        activeSlide: 0,
        totalSlides: {{ count($featuredNews) }},
        startAutoRotation() {
            setInterval(() => {
                this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
            }, 5000); // Change slide every 5 seconds
        }
    }
}
</script>
@endsection