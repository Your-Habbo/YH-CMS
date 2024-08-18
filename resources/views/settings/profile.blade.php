<div class="container mx-auto px-4 sm:px-6 lg:px-8 flex justify-center text-sm">
    <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-12 gap-6">
        <aside class="lg:col-span-3">
            @include('settings.sidebar')
        </aside>
        <main id="content" class="lg:col-span-9">
            <div class="card overflow-hidden sm: sm:px-8 pb-10">
                <div class="pt-4">
                    <h1 class="py-2 text-xl font-semibold">Profile settings</h1> 
                </div>
                <hr class="mt-4 mb-8" />
                <div class=" space-y-6">
                    <!-- Moments of Triumph (MOT) -->
                    <section>
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Moment of Triumph (MOT)</h2>
                        <form method="POST" action="{{ route('settings.updateMot') }}">
                            @csrf
                            <div class="space-y-3">
                                <textarea name="mot" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" rows="3" placeholder="Describe your proudest achievement...">{{ old('mot', $user->mot) }}</textarea>
                                <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white text-sm hover:bg-blue-700 transition duration-150 ease-in-out">Save MOT</button>
                            </div>
                        </form>
                    </section>

                    <!-- Forum Signature -->
                    <section>
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Forum Signature</h2>
                        <form method="POST" action="{{ route('settings.updateForumSignature') }}">
                            @csrf
                            <div class="space-y-3">
                                <textarea id="forum_signature" name="forum_signature" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" rows="3">{{ old('forum_signature', $user->forum_signature) }}</textarea>
                                <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white text-sm hover:bg-blue-700 transition duration-150 ease-in-out">Save Signature</button>
                            </div>
                        </form>
                        <div class="mt-4">
                            <h3 class="text-md font-semibold text-gray-700 mb-2">Signature Preview</h3>
                            <div id="signature_preview" class="border border-gray-300 rounded-md p-4 bg-gray-50 min-h-[100px]"></div>
                        </div>
                    </section>

                    <!-- Profile Banner Image -->
                    <section>
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Profile Banner Image</h2>
                        <form action="{{ route('settings.updateProfileBanner') }}" method="POST">
                            @csrf
                            <div class="space-y-3">
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                    @foreach($banners as $banner)
                                        <div class="relative">
                                            <img src="{{ asset('storage/' . $banner->filepath) }}" 
                                                 class="w-full h-24 object-cover rounded-md border-2 {{ $user->profile_banner == $banner->filepath ? 'border-blue-600' : 'border-gray-300' }} cursor-pointer hover:border-blue-600 transition duration-150 ease-in-out">
                                            <input type="radio" name="banner_image" value="{{ $banner->filepath }}" 
                                                   class="absolute top-2 right-2" 
                                                   {{ $user->profile_banner == $banner->filepath ? 'checked' : '' }}>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white text-sm hover:bg-blue-700 transition duration-150 ease-in-out">Save Banner Image</button>
                            </div>
                        </form>
                    </section>

                    <!-- Link Habbo Origin Account -->
                    <section>
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Habbo Origin Account</h2>
                        <div class="space-y-3">
                            @if ($user->habboLink)
                                <div class="flex items-center justify-between {{ $user->habboLink->habbo_origin_status === 'Verified' ? 'bg-green-100 border-green-400 text-green-700' : ($user->habboLink->habbo_origin_status === 'Pending' ? 'bg-yellow-100 border-yellow-400 text-yellow-700' : 'bg-red-100 border-red-400 text-red-700') }} px-4 py-2 rounded-md">
                                    <div>
                                        <p><strong>{{ $user->habboLink->habbo_origin_name }}</strong> is linked to your account.</p>
                                        <p>Status: <strong>{{ $user->habboLink->habbo_origin_status }}</strong></p>
                                    </div>
                                    <button id="remove-link-btn" class="bg-red-600 text-white px-3 py-1 rounded-md text-sm hover:bg-red-700 transition duration-150 ease-in-out">Remove</button>
                                </div>
                                @if ($user->habboLink->habbo_origin_status === 'Pending')
                                    <button id="show-motd-btn" class="rounded-md bg-blue-600 px-4 py-2 text-white text-sm hover:bg-blue-700 transition duration-150 ease-in-out">Show MOTD Code</button>
                                @endif
                            @else
                                <button id="link-habbo-btn" class="rounded-md bg-blue-600 px-4 py-2 text-white text-sm hover:bg-blue-700 transition duration-150 ease-in-out">Link Habbo Origin Account</button>
                            @endif
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</div>


<!-- Modal for linking Habbo account -->
<div id="habbo-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-lg">
        <h2 class="text-2xl font-semibold mb-4">Link Habbo Origin Account</h2>
        <form id="habbo-link-form">
            <label for="habbo-username" class="block text-sm font-medium text-gray-700">Habbo Username</label>
            <input type="text" id="habbo-username" name="habbo_username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <button type="submit" class="mt-4 rounded-lg bg-blue-600 px-4 py-2 text-white">Verify and Link</button>
        </form>
        <button id="close-modal" class="mt-4 text-sm text-red-600 underline">Cancel</button>
    </div>
</div>

<!-- Modal for showing MOTD code -->
<div id="motd-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-lg">
        <h2 class="text-2xl font-semibold mb-4">MOTD Code</h2>
        <p>Please set your MOT in the game to: <strong id="motd-code"></strong></p>
        <p>Once you have set your MOT, we will verify your account. This process may take a few minutes.</p>
        <button id="close-motd-modal" class="mt-4 text-sm text-red-600 underline">Close</button>
    </div>
</div>

<!-- Confirmation Modal for Removing Habbo Link -->
<div id="remove-confirm-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-lg">
        <h2 class="text-2xl font-semibold mb-4">Confirm Removal</h2>
        <p>Are you sure you want to remove the linked Habbo Origin account?</p>
        <div class="mt-4 flex justify-end">
            <button id="confirm-remove" class="bg-red-600 text-white px-4 py-2 rounded-lg mr-2">Remove</button>
            <button id="cancel-remove" class="bg-gray-300 text-black px-4 py-2 rounded-lg">Cancel</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Show Link Modal
    document.getElementById('link-habbo-btn')?.addEventListener('click', function() {
        document.getElementById('habbo-modal').classList.remove('hidden');
    });

    // Close Link Modal
    document.getElementById('close-modal')?.addEventListener('click', function() {
        document.getElementById('habbo-modal').classList.add('hidden');
    });

    // Handle Habbo Link Form Submission
    document.getElementById('habbo-link-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        var username = document.getElementById('habbo-username').value;

        $.ajax({
            url: "{{ route('settings.linkHabbo') }}",
            type: "POST",
            data: {
                username: username,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $('#habbo-link-form').html(`
                    <h2 class="text-xl font-semibold mb-4">Verification In Progress</h2>
                    <p>Please set your MOT in the game to: <strong>${response.verification_code}</strong></p>
                    <p>Once you have set your MOT, we will verify your account. This process may take a few minutes.</p>
                    <button id="close-modal" class="mt-4 text-sm text-red-600 underline">Close</button>
                `);

                document.getElementById('close-modal').addEventListener('click', function() {
                    document.getElementById('habbo-modal').classList.add('hidden');
                    location.reload(); // Reload the page to reflect any changes
                });
            },
            error: function(xhr) {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Show MOTD Code
    document.getElementById('show-motd-btn')?.addEventListener('click', function() {
        $.ajax({
            url: "{{ route('settings.getMotdCode') }}",
            type: "GET",
            success: function(response) {
                document.getElementById('motd-code').textContent = response.motd_code;
                document.getElementById('motd-modal').classList.remove('hidden');
            },
            error: function(xhr) {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Cancel Linking Process
    document.getElementById('cancel-link-btn')?.addEventListener('click', function() {
        if (confirm('Are you sure you want to cancel the linking process?')) {
            $.ajax({
                url: "{{ route('settings.cancelHabboLink') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    alert(response.message);
                    location.reload(); // Refresh the page
                },
                error: function(xhr) {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });

    // Open Remove Confirmation Modal
    document.getElementById('remove-link-btn')?.addEventListener('click', function() {
        document.getElementById('remove-confirm-modal').classList.remove('hidden');
    });

    // Confirm Removal
    document.getElementById('confirm-remove')?.addEventListener('click', function() {
        $.ajax({
            url: "{{ route('settings.removeHabboLink') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                alert(response.message);
                location.reload(); // Refresh the page
            },
            error: function(xhr) {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Cancel Removal Process
    document.getElementById('cancel-remove')?.addEventListener('click', function() {
        document.getElementById('remove-confirm-modal').classList.add('hidden');
    });

    // Close MOTD Modal
    document.getElementById('close-motd-modal')?.addEventListener('click', function() {
        document.getElementById('motd-modal').classList.add('hidden');
    });
});
</script>
<!-- Add Trumbowyg CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trumbowyg@2.25.1/dist/ui/trumbowyg.min.css">
<script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.25.1/dist/trumbowyg.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Trumbowyg
        $('#forum_signature').trumbowyg({
            btns: [
                ['formatting'],
                ['strong', 'em', 'del'],
                ['link'],
                ['justifyLeft', 'justifyCenter', 'justifyRight'],
                ['unorderedList', 'orderedList'],
                ['fullscreen']
            ],
            autogrow: true,
            removeformatPasted: true,
            changeActiveDropdownIcon: true,
            semantic: false,
            tagsToRemove: ['script', 'link'],
            urlProtocol: true,
            minimalLinks: true
        });
    
        // Function to update signature preview
        function updateSignaturePreview() {
            const signatureContent = $('#forum_signature').trumbowyg('html');
            $('#signature_preview').html(signatureContent);
        }
    
        // Update preview on Trumbowyg change event
        $('#forum_signature').on('tbwchange', updateSignaturePreview);
    
        // Initial preview update
        updateSignaturePreview();
    
    });
    </script>