<div id="image-manager-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
        <h2 class="text-lg font-semibold mb-4">Select an Image</h2>
        <div class="grid grid-cols-3 gap-4">
            @foreach($images as $image)
                <div class="border rounded-lg overflow-hidden shadow-sm cursor-pointer" onclick="selectImage('{{ $image->filepath }}')">
                    <img src="{{ asset('storage/' . $image->filepath) }}" alt="{{ $image->filename }}" class="w-full h-32 object-cover">
                </div>
            @endforeach
        </div>
        <button type="button" class="mt-4 bg-red-500 text-white px-4 py-2 rounded" onclick="document.getElementById('image-manager-modal').classList.add('hidden')">Close</button>
    </div>
</div>
