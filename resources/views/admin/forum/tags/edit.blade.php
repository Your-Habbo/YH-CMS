@extends('admin.layouts.app')

@section('content')
<main class="flex-1 overflow-y-auto bg-gray-100">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold">Edit Tag</h1>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mt-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.forum-tags.update', $forumTag->id) }}" method="POST" class="mt-4">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $forumTag->name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label for="color" class="block text-sm font-medium text-gray-700">Tag Color</label>
                <input type="color" name="color" id="color" value="{{ old('color', $forumTag->color) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <input type="checkbox" name="is_help_tag" id="is_help_tag" class="rounded" {{ $forumTag->is_help_tag ? 'checked' : '' }}>
                <label for="is_help_tag" class="text-sm font-medium text-gray-700">Is Help Tag</label>
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Update Tag</button>
        </form>
    </div>
</main>
@endsection
