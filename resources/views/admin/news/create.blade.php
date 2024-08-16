@extends('admin.layouts.app')

@section('content')

<main class="flex-1 overflow-y-auto bg-gray-100"> 
    @component('components.admin-breadcrumbs', ['breadcrumbs' => $breadcrumbs])
    @endcomponent
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold">Create New Article</h1>

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 mt-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form to Create a New Article -->
        <form action="{{ route('admin.news.store') }}" method="POST" class="mt-6">
            @csrf

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                <textarea name="content" id="content" rows="10" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('content') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Featured Image</label>
                <div class="flex items-center">
                    <input type="hidden" name="image" id="image" value="{{ old('image') }}">
                    <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded" id="select-image-button">Select Image</button>
                    <img id="image-preview" src="{{ old('image') ? asset('storage/' . old('image')) : '' }}" alt="Selected Image" class="ml-4 w-32 h-auto" style="display: {{ old('image') ? 'block' : 'none' }};">
                </div>
            </div>

            <div class="mb-4">
                <label for="published_at" class="block text-sm font-medium text-gray-700">Publish Date</label>
                <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <input type="checkbox" name="is_featured" id="is_featured" class="rounded">
                <label for="is_featured" class="text-sm font-medium text-gray-700">Featured Article</label>
            </div>

            <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded" id="preview-button">Preview</button>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Create Article</button>
           
        </form>
    </div>
</main>

<!-- Include the image manager modal -->
@include('admin.images.modal')

<!-- Include Trumbowyg -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trumbowyg@2.25.1/dist/ui/trumbowyg.min.css">
<script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.25.1/dist/trumbowyg.min.js"></script>

<script>
    // Initialize Trumbowyg on the content textarea
    $('#content').trumbowyg();

    document.getElementById('select-image-button').addEventListener('click', function() {
        // Open the image manager modal
        document.getElementById('image-manager-modal').classList.remove('hidden');
    });

    function selectImage(imageUrl) {
        // Set the selected image URL to the hidden input
        document.getElementById('image').value = imageUrl;

        // Update the image preview
        const imagePreview = document.getElementById('image-preview');
        imagePreview.src = '{{ asset('storage') }}/' + imageUrl;
        imagePreview.style.display = 'block';

        // Close the modal
        document.getElementById('image-manager-modal').classList.add('hidden');
    }

    // Make sure to close the modal if the user clicks outside it
    document.getElementById('image-manager-modal').addEventListener('click', function(event) {
        if (event.target.id === 'image-manager-modal') {
            document.getElementById('image-manager-modal').classList.add('hidden');
        }
    });
document.getElementById('preview-button').addEventListener('click', function() {
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.news.preview") }}';
    form.target = '_blank';

    @csrf
    form.appendChild(createInput('_token', '{{ csrf_token() }}'));
    form.appendChild(createInput('title', document.getElementById('title').value));
    form.appendChild(createInput('content', tinymce.activeEditor.getContent()));  // or $('#content').trumbowyg('html') for Trumbowyg
    form.appendChild(createInput('image', document.getElementById('image').value));
    form.appendChild(createInput('published_at', document.getElementById('published_at').value));
    form.appendChild(createInput('is_featured', document.getElementById('is_featured').checked ? 1 : 0));

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
});

function createInput(name, value) {
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = value;
    return input;
}
</script>


@endsection
