@extends('layouts.app')

@section('content')
<main class="container mx-auto py-10">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6">{{ $news->title }}</h1>
        <p class="text-gray-700 mb-6">{{ $news->content }}</p>

        @if ($news->image)
            <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="w-full h-auto rounded-lg">
        @endif

        <div class="mt-6">
            <a href="{{ route('news.index') }}" class="text-blue-600 hover:underline">Back to News</a>
        </div>
    </div>
</main>
@endsection
