@extends('admin.layouts.app')

@section('content')
<main class="flex-1 overflow-y-auto bg-gray-100">
    <!-- Breadcrumbs -->
    @component('components.admin-breadcrumbs', ['breadcrumbs' => $breadcrumbs])
    @endcomponent

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="border-4 border-dashed border-gray-200 rounded-lg h-auto p-4">
                <h2 class="text-2xl font-semibold mb-4">Image Management</h2>

                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Drag-and-Drop Upload Area -->
                <div x-data="fileUploader()" class="bg-white p-7 rounded w-full mx-auto">
                    <div class="relative flex flex-col p-4 text-gray-400 border border-gray-200 rounded">
                        <div x-ref="dropArea"
                            class="relative flex flex-col text-gray-400 border border-gray-200 border-dashed rounded cursor-pointer"
                            @dragover.prevent="dragging = true"
                            @dragleave.prevent="dragging = false"
                            @drop.prevent="handleDrop($event)">
                            <input type="file" accept="image/*" multiple
                                class="absolute inset-0 z-50 w-full h-full p-0 m-0 outline-none opacity-0 cursor-pointer"
                                @change="handleFiles($event)">
                            <div class="flex flex-col items-center justify-center py-10 text-center">
                                <svg class="w-6 h-6 mr-1 text-current-50" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="m-0" :class="{'text-blue-500': dragging}">Drag your files here or click to select.</p>
                            </div>
                        </div>
                        <template x-if="files.length > 0">
                            <div class="grid grid-cols-2 gap-4 mt-4 md:grid-cols-6">
                                <template x-for="(file, index) in files" :key="index">
                                    <div class="relative flex flex-col items-center overflow-hidden text-center bg-gray-100 border rounded select-none"
                                        style="padding-top: 100%;">
                                        <button type="button" class="absolute top-0 right-0 z-50 p-1 bg-white rounded-bl focus:outline-none"
                                            @click="removeFile(index)">
                                            <svg class="w-4 h-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        <template x-if="file.type.includes('image/')">
                                            <img class="absolute inset-0 z-0 object-cover w-full h-full border-4 border-white preview"
                                                :src="file.url" alt="">
                                        </template>
                                        <div class="absolute bottom-0 left-0 right-0 flex flex-col p-2 text-xs bg-white bg-opacity-50">
                                            <span class="w-full font-bold text-gray-900 truncate" x-text="file.name"></span>
                                            <span class="text-xs text-gray-900" x-text="file.sizeReadable"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <!-- Upload Button -->
                    <button type="button" @click="uploadFiles" :disabled="files.length === 0"
                        class="mt-4 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded disabled:bg-gray-300">
                        Upload Images
                    </button>
                </div>

                <!-- Image Grid with Bulk Actions -->
                <form action="{{ route('admin.images.bulkDelete') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete selected images?');">
                    @csrf
                    @method('DELETE')

                    <div class="mt-6 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-700">Your Images</h3>
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">Delete Selected</button>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-4">
                        @foreach ($images as $image)
                        <div class="border rounded-lg overflow-hidden shadow-sm bg-white relative">
                            <input type="checkbox" name="selected_images[]" value="{{ $image->id }}" class="absolute top-0 right-0 m-2">

                            <img src="{{ asset('storage/' . $image->filepath) }}" alt="{{ $image->filename }}" class="w-full h-32 object-cover">
                            <div class="p-2">
                                <p class="text-xs text-gray-700 truncate">{{ $image->filename }}</p>
                                <div class="mt-2 flex items-center justify-between">
                                    <button type="button" @click="openPreview('{{ asset('storage/' . $image->filepath) }}')"
                                        class="text-xs text-blue-500 hover:underline">Preview</button>
                                    <form action="{{ route('admin.images.destroy', $image->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this image?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $images->links() }}
                    </div>
                </form>

                <!-- Image Preview Modal (Hidden by default) -->
                <div x-data="{ open: false, imageUrl: '' }" @open-preview.window="open = true; imageUrl = $event.detail;">
                    <div x-show="open" @click.away="open = false" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-md w-full">
                            <img :src="imageUrl" alt="Preview Image" class="w-full h-auto">
                            <div class="p-4 flex justify-end">
                                <button @click="open = false" class="text-sm text-gray-600 hover:text-gray-800">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<script>
    function fileUploader() {
        return {
            files: [],
            dragging: false,

            handleFiles(event) {
                const selectedFiles = event.target.files;
                this.addFiles(selectedFiles);
            },

            handleDrop(event) {
                const droppedFiles = event.dataTransfer.files;
                this.addFiles(droppedFiles);
                this.dragging = false;
            },

            addFiles(files) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    file.url = URL.createObjectURL(file); // Create a URL for the image preview
                    file.sizeReadable = this.humanFileSize(file.size); // Convert the file size
                    this.files.push(file);
                }
            },

            removeFile(index) {
                this.files.splice(index, 1);
            },

            humanFileSize(size) {
                const i = Math.floor(Math.log(size) / Math.log(1024));
                return (
                    (size / Math.pow(1024, i)).toFixed(2) * 1 +
                    " " +
                    ["B", "kB", "MB", "GB", "TB"][i]
                );
            },

            uploadFiles() {
                const formData = new FormData();
                this.files.forEach(file => {
                    formData.append('images[]', file);
                });

                fetch('{{ route('admin.images.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then(response => {
                    if (response.ok) {
                        alert('Images uploaded successfully.');
                        location.reload(); // Reload the page to see the uploaded images
                    } else {
                        alert('Failed to upload images.');
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while uploading images.');
                });
            },
        };
    }

    function openPreview(url) {
        window.dispatchEvent(new CustomEvent('open-preview', { detail: url }));
    }
</script>

@endsection
