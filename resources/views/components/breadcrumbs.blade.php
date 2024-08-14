<!-- resources/views/components/breadcrumbs.blade.php -->
@if(isset($breadcrumbs) && count($breadcrumbs))
    <div class="breadcrumb-card w-full">
        <nav class="breadcrumb px-5 py-3 text-gray-700 bg-white border border-gray-200 rounded-lg shadow-lg mb-12" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 w-full">
                @foreach($breadcrumbs as $breadcrumb)
                    <li class="inline-flex items-center">
                        @if(!$loop->last)
                            <a href="{{ $breadcrumb['url'] }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                {{ $breadcrumb['label'] }}
                            </a>
                        @else
                            <span class="text-sm font-medium text-gray-500">
                                {{ $breadcrumb['label'] }}
                            </span>
                        @endif

                        @if(!$loop->last)
                            <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>
@endif
