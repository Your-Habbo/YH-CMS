@pjax('layouts.admin')

@section('title', 'Create Page')

<style>
    .nested {
        border: 2px dashed #e2e8f0;
        padding: 10px;
        margin-bottom: 10px;
        position: relative;
    }

    .resize-handle {
        position: absolute;
        right: -8px;
        bottom: -8px;
        width: 16px;
        height: 16px;
        background-color: #4a5568;
        cursor: se-resize;
        border-radius: 50%;
    }

    .highlight-dropzone {
        border: 2px dashed #63b3ed;
        background-color: #ebf8ff;
    }
</style>

<h1>{{ isset($page) ? 'Edit Page' : 'Create New Page' }}</h1>

<form action="{{ isset($page) ? route('admin.pages.update', $page->id) : route('admin.pages.store') }}" method="POST" id="page-form">
    @csrf
    @if(isset($page))
        @method('PUT')
    @endif

    <div>
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="{{ old('title', $page->title ?? '') }}" required>
    </div>

    <div>
        <label for="slug">Slug</label>
        <input type="text" name="slug" id="slug" value="{{ old('slug', $page->slug ?? '') }}" required>
    </div>

    <div>
        <label for="layout">Layout</label>
        <select name="layout" id="layout" required>
            <option value="layouts.page1" {{ old('layout', $page->layout ?? '') == 'layouts.page1' ? 'selected' : '' }}>Page Layout 1</option>
            <option value="layouts.page2" {{ old('layout', $page->layout ?? '') == 'layouts.page2' ? 'selected' : '' }}>Page Layout 2</option>
            <!-- Add more layout options as needed -->
        </select>
    </div>

    <!-- Page Builder -->
    <div>
        <label for="content">Content</label>
        <div id="page-builder-container" class="mb-6">
            <!-- The HTML and JS for the page builder will be included here -->
            <div class="container mx-auto p-4">
                <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Advanced Page Builder</h1>
                
                <!-- Toolbar -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-6 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-700">Toolbar</h2>
                    <div class="space-x-2 flex items-center">
                        <div class="relative">
                            <button id="menuBtn" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                                Menu
                            </button>
                            <div id="menuDropdown" class="absolute top-10 right-0 bg-white shadow-lg rounded-lg p-4 hidden w-64 z-10">
                                <div class="mb-4">
                                    <h3 class="text-lg font-semibold text-gray-700">Layouts</h3>
                                    <div class="space-y-2">
                                        <div class="bg-gray-100 p-3 rounded-md cursor-move" draggable="true" data-type="grid-2">
                                            <div class="flex justify-between">
                                                <div class="w-1/2 h-8 bg-blue-200 rounded"></div>
                                                <div class="w-1/2 h-8 bg-blue-200 rounded"></div>
                                            </div>
                                            <p class="text-sm text-center mt-2 text-gray-600">2 Column Grid</p>
                                        </div>
                                        <div class="bg-gray-100 p-3 rounded-md cursor-move" draggable="true" data-type="grid-3">
                                            <div class="flex justify-between">
                                                <div class="w-1/3 h-8 bg-green-200 rounded"></div>
                                                <div class="w-1/3 h-8 bg-green-200 rounded"></div>
                                                <div class="w-1/3 h-8 bg-green-200 rounded"></div>
                                            </div>
                                            <p class="text-sm text-center mt-2 text-gray-600">3 Column Grid</p>
                                        </div>
                                        <div class="bg-gray-100 p-3 rounded-md cursor-move" draggable="true" data-type="custom-grid">
                                            <div class="flex justify-between">
                                                <div class="w-1/4 h-8 bg-red-200 rounded"></div>
                                                <div class="w-1/2 h-8 bg-red-200 rounded"></div>
                                                <div class="w-1/4 h-8 bg-red-200 rounded"></div>
                                            </div>
                                            <p class="text-sm text-center mt-2 text-gray-600">Custom Grid</p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-700">Components</h3>
                                    <div class="space-y-2">
                                        <div class="bg-gray-100 p-3 rounded-md cursor-move" draggable="true" data-type="text">
                                            <div class="h-8 bg-yellow-200 rounded flex items-center justify-center text-center">
                                                <p class="text-gray-800 text-sm">Sample Text Block</p>
                                            </div>
                                        </div>
                                        <div class="bg-gray-100 p-3 rounded-md cursor-move" draggable="true" data-type="image">
                                            <div class="h-8 bg-purple-200 rounded flex items-center justify-center">
                                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <p class="text-sm text-center mt-2 text-gray-600">Image Block</p>
                                        </div>
                                        <div class="bg-gray-100 p-3 rounded-md cursor-move" draggable="true" data-type="content-card-header">
                                            <div class="h-8 bg-blue-500 rounded flex items-center justify-center text-center">
                                                <p class="text-white text-sm">Content Card with Header</p>
                                            </div>
                                        </div>
                                        <div class="bg-gray-100 p-3 rounded-md cursor-move" draggable="true" data-type="content-card">
                                            <div class="h-8 bg-blue-300 rounded flex items-center justify-center text-center">
                                                <p class="text-gray-800 text-sm">Content Card without Header</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button id="undoBtn" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Undo
                        </button>
                        <button id="redoBtn" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Redo
                        </button>
                    </div>
                </div>

                <!-- Page Canvas -->
                <div class="w-full bg-white rounded-lg shadow-md p-4 min-h-[600px]" id="page-canvas">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">Canvas</h2>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 min-h-[500px] bg-gray-50 relative" id="dropzone">
                        <!-- Example header -->
                        <div class="bg-gray-800 text-white px-4 py-2 rounded-md mb-4">
                            <h3 class="text-lg">Website Header</h3>
                        </div>

                        <!-- Drop Area -->
                        <div class="flex-grow">
                            <p class="text-gray-400 text-center">Drag and drop components here</p>
                        </div>

                        <!-- Example footer -->
                        <div class="absolute bottom-0 w-full bg-gray-800 text-white px-4 py-2 rounded-md">
                            <p class="text-center">Website Footer</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Grid Modal -->
            <div id="customGridModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Custom Grid</h3>
                        <div class="mt-2 px-7 py-3">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="columnCount">
                                    Number of Columns:
                                </label>
                                <select id="columnCount" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="percentages">
                                    Percentages (comma-separated):
                                </label>
                                <input id="percentages" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="e.g. 30,70 or 25,50,25">
                            </div>
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" id="flipPercentages" class="form-checkbox">
                                    <span class="ml-2">Flip Percentages</span>
                                </label>
                            </div>
                        </div>
                        <div class="items-center px-4 py-3">
                            <button id="createCustomGridBtn" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                Create Grid
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Text Editor Modal -->
            <div id="textEditorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center">
                <div class="bg-white rounded-lg shadow-lg p-6 w-2/3">
                    <h2 class="text-xl mb-4">Edit Text</h2>
                    <textarea id="tinymce-editor"></textarea>
                    <div class="flex justify-end mt-4">
                        <button id="saveTextButton" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                        <button id="cancelTextButton" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- Color Picker Modal -->
            <div id="colorPickerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center">
                <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
                    <h2 class="text-xl mb-4">Select Card Header Color</h2>
                    <div class="flex space-x-4 mb-4">
                        <label class="flex items-center">
                            <input type="radio" name="color" value="bg-blue-500" class="form-radio">
                            <span class="ml-2 text-blue-500">Blue</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="color" value="bg-red-500" class="form-radio">
                            <span class="ml-2 text-red-500">Red</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="color" value="bg-green-500" class="form-radio">
                            <span class="ml-2 text-green-500">Green</span>
                        </label>
                        <!-- Add more colors as needed -->
                    </div>
                    <div class="flex justify-end">
                        <button id="applyColorBtn" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Apply</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- The serialized JSON content from the page builder -->
        <textarea name="content" id="content" class="hidden">{{ old('content', $page->content ?? '') }}</textarea>
    </div>  
    <div>
        <button type="submit">{{ isset($page) ? 'Update Page' : 'Create Page' }}</button>
    </div>

    <div>
        <label for="custom_css">Custom CSS</label>
        <textarea name="custom_css" id="custom_css">{{ old('custom_css', $page->custom_css ?? '') }}</textarea>
    </div>

    <div>
        <label for="custom_js">Custom JavaScript</label>
        <textarea name="custom_js" id="custom_js">{{ old('custom_js', $page->custom_js ?? '') }}</textarea>
    </div>


</form>

<script src="https://cdn.tiny.cloud/1/7vwtaufsqsiz5mt5lg92szt0yc49hnl04obsw5nv35l5zyfq/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    // Initialize the page builder with existing content if editing
    document.addEventListener('DOMContentLoaded', function () {
        const contentTextarea = document.getElementById('content');
        const initialContent = contentTextarea.value;

        function saveState() {
            stateHistory = stateHistory.slice(0, currentStateIndex + 1);
            stateHistory.push(dropzone.innerHTML);
            currentStateIndex++;
            updateUndoRedoButtons();
        }

        function updateUndoRedoButtons() {
            undoBtn.disabled = currentStateIndex <= 0;
            redoBtn.disabled = currentStateIndex >= stateHistory.length - 1;
        }

        function undo() {
            if (currentStateIndex > 0) {
                currentStateIndex--;
                dropzone.innerHTML = stateHistory[currentStateIndex];
                updateUndoRedoButtons();
                makeDroppable(dropzone);
            }
        }

        function redo() {
            if (currentStateIndex < stateHistory.length - 1) {
                currentStateIndex++;
                dropzone.innerHTML = stateHistory[currentStateIndex];
                updateUndoRedoButtons();
                makeDroppable(dropzone);
            }
        }

        // Initialize TinyMCE
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#tinymce-editor',
                plugins: 'table lists link image charmap preview',
                toolbar: 'undo redo | styles | bold italic underline | fontselect fontsizeselect | forecolor backcolor | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | table | link image | preview',
                menubar: false,
                statusbar: false,
                height: 300
            });
        }

        // Initialize the page builder
        const dropzone = document.getElementById('dropzone');
        const undoBtn = document.getElementById('undoBtn');
        const redoBtn = document.getElementById('redoBtn');
        const customGridModal = document.getElementById('customGridModal');
        const createCustomGridBtn = document.getElementById('createCustomGridBtn');
        const menuBtn = document.getElementById('menuBtn');
        const menuDropdown = document.getElementById('menuDropdown');
        const colorPickerModal = document.getElementById('colorPickerModal');
        const applyColorBtn = document.getElementById('applyColorBtn');

        let menuOpen = false;
        let stateHistory = [];
        let currentStateIndex = -1;
        let currentTextBlock = null;
        let customGridTarget = null;
        let currentCardHeader = null;

        // Toggle dropdown menu
        menuBtn.addEventListener('click', () => {
            menuOpen = !menuOpen;
            menuDropdown.classList.toggle('hidden', !menuOpen);
        });

        // Close the menu when clicking outside
        document.addEventListener('click', (e) => {
            if (menuOpen && !menuBtn.contains(e.target) && !menuDropdown.contains(e.target)) {
                menuOpen = false;
                menuDropdown.classList.add('hidden');
            }
        });

        // Set up drag and drop
        document.querySelectorAll('[draggable="true"]').forEach(draggable => {
            draggable.addEventListener('dragstart', dragStart);
        });

        dropzone.addEventListener('dragover', dragOver);
        dropzone.addEventListener('drop', drop);

        // Event delegation for delete button and color picker
        dropzone.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-btn')) {
                e.target.parentElement.remove();
                saveState();
            } else if (e.target.classList.contains('text-block')) {
                openTextEditor(e.target);
            } else if (e.target.classList.contains('card-header')) {
                currentCardHeader = e.target;
                colorPickerModal.classList.remove('hidden');
            }
        });

        function dragStart(e) {
            e.dataTransfer.setData('text/plain', e.target.getAttribute('data-type'));
            e.dataTransfer.effectAllowed = 'move';
        }

        function dragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            if (e.target.classList.contains('droppable')) {
                e.target.classList.add('highlight-dropzone');
            }
        }

        function dragLeave(e) {
            if (e.target.classList.contains('droppable')) {
                e.target.classList.remove('highlight-dropzone');
            }
        }

        function drop(e) {
            e.preventDefault();
            const componentType = e.dataTransfer.getData('text');
            let target = e.target;

            // Remove the highlight effect
            if (target.classList.contains('droppable')) {
                target.classList.remove('highlight-dropzone');
            }

            // Avoid adding component if dropped on non-droppable area
            if (!target.classList.contains('droppable') && target.id !== 'dropzone') {
                return;
            }

            // Remove placeholder text if present
            if (target.classList.contains('card-content') && target.innerText.trim() === 'Card content goes here...') {
                target.innerText = '';
            }

            // Track the drop target if custom grid is being added
            if (componentType === 'custom-grid') {
                customGridTarget = target;
                customGridModal.classList.remove('hidden');
            } else {
                // Create the component only once
                const newElement = createComponent(componentType);
                if (newElement) {
                    target.appendChild(newElement);
                    saveState();
                }
            }
        }

        function createComponent(type) {
            const div = document.createElement('div');
            div.classList.add('nested');

            // Add delete button
            const deleteButton = document.createElement('button');
            deleteButton.innerHTML = 'X';
            deleteButton.classList.add('delete-btn', 'absolute', 'top-0', 'right-0', 'bg-red-500', 'text-white', 'rounded-full', 'w-6', 'h-6', 'flex', 'items-center', 'justify-center');
            div.appendChild(deleteButton);
            
            switch(type) {
                case 'grid-2':
                    div.innerHTML += '<div class="flex space-x-4"><div class="w-1/2 h-20 bg-blue-200 rounded droppable"></div><div class="w-1/2 h-20 bg-blue-200 rounded droppable"></div></div>';
                    break;
                case 'grid-3':
                    div.innerHTML += '<div class="flex space-x-4"><div class="w-1/3 h-20 bg-green-200 rounded droppable"></div><div class="w-1/3 h-20 bg-green-200 rounded droppable"></div><div class="w-1/3 h-20 bg-green-200 rounded droppable"></div></div>';
                    break;
                case 'custom-grid':
                    return null;
                case 'text':
                    div.innerHTML += '<div class="bg-yellow-200 rounded p-2 text-block droppable" contenteditable="true">Edit this text</div>';
                    break;
                case 'image':
                    div.innerHTML += '<div class="bg-purple-200 rounded flex items-center justify-center p-2 droppable"><input type="file" accept="image/*" class="hidden" onchange="loadFile(event)"><label for="file" class="cursor-pointer">Click to upload image</label></div>';
                    const input = div.querySelector('input');
                    input.addEventListener('change', loadFile);
                    break;
                case 'content-card-header':
                    div.innerHTML += `
                        <div class="card mb-8 droppable">
                            <div class="card-header bg-blue-500 droppable" contenteditable="true">
                                <h2 class="text-l font-bold text-white" contenteditable="true">Card Title</h2>
                            </div>
                            <div class="card-content p-6 droppable">
                                Card content goes here...
                            </div>
                        </div>`;
                    break;
                case 'content-card':
                    div.innerHTML += `
                        <div class="card mb-8 droppable">
                            <h2 class="text-l font-bold text-white" contenteditable="true">Card Title</h2>
                            <div class="card-content p-6 droppable">
                                Card content goes here...
                            </div>
                        </div>`;
                    break;
            }

            // Add resize handle for resizing the layout
            const resizeHandle = document.createElement('div');
            resizeHandle.classList.add('resize-handle');
            div.appendChild(resizeHandle);

            makeDroppable(div); // Ensure new elements are droppable
            return div;
        }

        function openTextEditor(textBlock) {
            currentTextBlock = textBlock;
            tinymce.get('tinymce-editor').setContent(textBlock.innerHTML);
            document.getElementById('textEditorModal').classList.remove('hidden');
        }

        document.getElementById('saveTextButton').addEventListener('click', function() {
            if (currentTextBlock) {
                currentTextBlock.innerHTML = tinymce.get('tinymce-editor').getContent();
                saveState();
            }
            document.getElementById('textEditorModal').classList.add('hidden');
            currentTextBlock = null;
        });

        document.getElementById('cancelTextButton').addEventListener('click', function() {
            document.getElementById('textEditorModal').classList.add('hidden');
            currentTextBlock = null;
        });

        createCustomGridBtn.addEventListener('click', () => {
            const columnCount = document.getElementById('columnCount').value;
            let percentages = document.getElementById('percentages').value.split(',').map(Number);
            const flipPercentages = document.getElementById('flipPercentages').checked;

            if (percentages.length !== parseInt(columnCount)) {
                alert('The number of percentages should match the number of columns.');
                return;
            }

            if (percentages.reduce((a, b) => a + b, 0) !== 100) {
                alert('Percentages should add up to 100.');
                return;
            }

            if (flipPercentages) {
                percentages = percentages.reverse();
            }

            const newGrid = createCustomGrid(columnCount, percentages);
            if (customGridTarget) {
                customGridTarget.appendChild(newGrid);
                saveState();
                customGridTarget = null;
            }
            customGridModal.classList.add('hidden');
        });

        function createCustomGrid(columnCount, percentages) {
            const div = document.createElement('div');
            div.classList.add('flex', 'space-x-4', 'mb-4');
            
            for (let i = 0; i < columnCount; i++) {
                const column = document.createElement('div');
                column.style.flexBasis = percentages[i] + '%';
                column.classList.add('h-20', 'bg-red-200', 'rounded', 'droppable');
                div.appendChild(column);
            }

            makeDroppable(div);
            
            return div;
        }

        function loadFile(event) {
            const output = document.createElement('img');
            output.classList.add('max-w-full', 'h-auto');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src); // free memory
            };
            event.target.parentElement.appendChild(output);
            event.target.parentElement.removeChild(event.target.previousElementSibling); // Remove the label
            event.target.parentElement.removeChild(event.target); // Remove the input
        }

        // Handle color selection for card header
        applyColorBtn.addEventListener('click', () => {
            const selectedColor = document.querySelector('input[name="color"]:checked').value;
            if (currentCardHeader) {
                currentCardHeader.className = `card-header ${selectedColor}`;
                saveState();
                colorPickerModal.classList.add('hidden');
                currentCardHeader = null;
            }
        });

        // Make all grid cells and content card areas droppable
        function makeDroppable(element) {
            if (element.classList.contains('droppable')) {
                element.addEventListener('dragover', dragOver);
                element.addEventListener('dragleave', dragLeave);
                element.addEventListener('drop', drop);
            }
            element.childNodes.forEach(child => {
                if (child.nodeType === 1) { // if it's an element node
                    makeDroppable(child);
                }
            });
        }

        // Initial state
        saveState();

        // Ensure dropzone and any new droppable areas are always droppable
        makeDroppable(dropzone);

        // Before submitting the form, update the content textarea with the current state of the page builder
        document.getElementById('page-form').addEventListener('submit', function() {
            contentTextarea.value = dropzone.innerHTML;
        });
    });
</script>