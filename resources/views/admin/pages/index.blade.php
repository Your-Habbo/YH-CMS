@pjax('layouts.admin')


@section('title', 'Manage Pages')


    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Pages</h1>

        <!-- Flash message for success -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="mb-4">
            <a href="{{ route('admin.pages.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create New Page
            </a>
        </div>

        <!-- Pages Table -->
        <div class="bg-white shadow-md rounded my-6">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="w-full bg-gray-800 text-white">
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">ID</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Title</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Slug</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Layout</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($pages as $page)
                        <tr>
                            <td class="text-left py-3 px-4">{{ $page->id }}</td>
                            <td class="text-left py-3 px-4">{{ $page->title }}</td>
                            <td class="text-left py-3 px-4">{{ $page->slug }}</td>
                            <td class="text-left py-3 px-4">{{ $page->layout }}</td>
                            <td class="text-left py-3 px-4">
                                <a href="{{ route('pages.show', $page->slug) }}" class="text-blue-600 hover:text-blue-900">View</a> |
                                <a href="{{ route('admin.pages.edit', $page->id) }}" class="text-green-600 hover:text-green-900">Edit</a> |
                                <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this page?');">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-3 px-4">No pages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
