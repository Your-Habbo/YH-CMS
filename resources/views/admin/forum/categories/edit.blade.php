@pjax('layouts.admin')


<main class="flex-1 overflow-y-auto bg-gray-100">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold">Edit Forum Category</h1>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mt-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.forum-categories.update', $forumCategory->id) }}" method="POST" class="mt-4">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $forumCategory->name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>  

            <div class="mb-4">
                <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $forumCategory->slug) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $forumCategory->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Category Image</label>
                <div class="flex items-center">
                    <input type="hidden" name="image" id="image" value="{{ old('image', $forumCategory->image) }}">
                    <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded" id="select-image-button">Select Image</button>
                    <img id="image-preview" src="{{ $forumCategory->image ? asset('storage/' . $forumCategory->image) : '' }}" alt="Selected Image" class="ml-4 w-32 h-auto" style="display: {{ $forumCategory->image ? 'block' : 'none' }};">
                </div>
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Update Category</button>
        </form>
    </div>
</main>

<!-- Include the image manager modal -->
@include('admin.images.modal')

<script>
document.getElementById('select-image-button').addEventListener('click', function() {
    document.getElementById('image-manager-modal').classList.remove('hidden');
});

function selectImage(imageUrl) {
    document.getElementById('image').value = imageUrl;
    const imagePreview = document.getElementById('image-preview');
    imagePreview.src = '{{ asset('storage') }}/' + imageUrl;
    imagePreview.style.display = 'block';
    document.getElementById('image-manager-modal').classList.add('hidden');
}

document.getElementById('image-manager-modal').addEventListener('click', function(event) {
    if (event.target.id === 'image-manager-modal') {
        document.getElementById('image-manager-modal').classList.add('hidden');
    }
});
</script>

