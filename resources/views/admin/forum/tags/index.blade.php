@pjax('layouts.admin')


<main class="flex-1 overflow-y-auto bg-gray-100">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold">Thread Tags</h1>

        <a href="{{ route('admin.forum-tags.create') }}" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Create New Tag</a>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <ul>
                @foreach ($tags as $tag)
                    <li class="border-t border-gray-200">
                        <div class="flex items-center justify-between px-4 py-4 sm:px-6">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $tag->name }} - <span style="color: {{ $tag->color }}">{{ $tag->color }}</span>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <a href="{{ route('admin.forum-tags.edit', $tag) }}" class="text-blue-500 hover:underline">Edit</a>
                                <form action="{{ route('admin.forum-tags.destroy', $tag) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this tag?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline ml-2">Delete</button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</main>

