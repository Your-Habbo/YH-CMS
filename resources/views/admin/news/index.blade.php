@pjax('layouts.admin')

<main class="flex-1 overflow-y-auto bg-gray-100">
@component('components.admin-breadcrumbs', ['breadcrumbs' => $breadcrumbs])
@endcomponent
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold">News Articles</h1>
        <a href="{{ route('admin.news.create') }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Create New Article</a>
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 mt-4">
                {{ session('success') }}
            </div>
        @endif
        <div class="mt-8 flex flex-col">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Published At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($news as $article)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $article->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $article->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $article->published_at }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.news.edit', $article->id) }}" class="text-blue-500 hover:underline">Edit</a>
                                <form action="{{ route('admin.news.destroy', $article->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

