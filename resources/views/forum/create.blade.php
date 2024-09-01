@pjax('layouts.app')

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:p-8">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-6">Create New Thread</h1>

                <form action="{{ route('forum.store') }}" method="POST" class="space-y-6" id="create-thread-form">
                    @csrf

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        <p id="title-counter" class="mt-2 text-sm text-gray-500"></p>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tags -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($tags as $tag)
                                <div class="flex items-center tag-checkbox">
                                    <input type="checkbox" name="tags[]" id="tag_{{ $tag->id }}" value="{{ $tag->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="tag_{{ $tag->id }}" class="ml-2 text-sm text-gray-600">{{ $tag->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                        <div class="mt-1">
                            <textarea id="content" name="content" rows="6" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" required></textarea>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Write your thread content here. You can use Markdown for formatting.</p>
                    </div>

                    <!-- Options -->
                    <div class="space-y-4">
                        @can('create_sticky_threads')
                            <div class="flex items-center">
                                <input type="checkbox" name="is_sticky" id="is_sticky" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="is_sticky" class="ml-2 text-sm text-gray-600">Make this thread sticky</label>
                            </div>
                        @endcan

                        <div class="flex items-center">
                            <input type="checkbox" name="requires_solution" id="requires_solution" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="requires_solution" class="ml-2 text-sm text-gray-600">This thread requires a solution</label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-5">
                        <div class="flex justify-end">
                            <a href="{{ route('forum.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Thread
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>





<script>
    document.addEventListener('DOMContentLoaded', function() {
        const easyMDE = new EasyMDE({element: document.getElementById('content')});

        // Preview character count
        const titleInput = document.getElementById('title');
        const titleCounter = document.getElementById('title-counter');

        titleInput.addEventListener('input', function() {
            const remaining = 100 - this.value.length;
            titleCounter.textContent = `${remaining} characters remaining`;
        });

        // Enhance tag selection
        const tagCheckboxes = document.querySelectorAll('.tag-checkbox input[type="checkbox"]');
        tagCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                this.closest('.tag-checkbox').classList.toggle('bg-indigo-100', this.checked);
            });
        });
</script>
