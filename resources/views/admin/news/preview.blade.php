@pjax('layouts.admin')

<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8"
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            @if($news->image)
                <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="mb-4 w-full h-auto">
            @endif

            <h1 class="text-2xl font-bold mb-2">{{ $news->title }}</h1>

            <p class="text-gray-600 text-sm mb-4">Published on {{ $news->published_at->format('F j, Y, g:i a') }}</p>

            <div class="content">
                {!! $news->content !!}
            </div>
        </div>
    </div>
</main>
