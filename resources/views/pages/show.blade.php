{{-- resources/views/pages/show.blade.php --}}
@pjax('layouts.app')
@section('title', '{{ $page->title }}')

<main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto">

                        {!! $page->content !!}
      
        
            </div>
        </main>
        
@push('styles')
    <style>
        {!! $page->custom_css !!}
    </style>
@endpush

@push('scripts')
    <script>
        {!! $page->custom_js !!}
    </script>
@endpush
