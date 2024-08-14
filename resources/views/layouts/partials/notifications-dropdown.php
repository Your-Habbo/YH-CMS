<div class="relative dropdown">
    <a href="#" class="nav-text hover:text-gray-300 drop-shadow-2xl uppercase relative">
        <i class="fas fa-bell"></i>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute top-0 right-0 -mt-1 -mr-1 px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </a>
    <div class="dropdown-menu">
        <div class="dropdown-menu-inner">
            @php
                $notifications = auth()->user()->notifications()->latest()->take(6)->get();
            @endphp
            @forelse($notifications as $notification)
                <div class="menu-item {{ $notification->read_at ? 'opacity-50' : '' }}">
                    <span>{{ Str::limit($notification->data['message'], 30) }}</span>
                    <div class="flex items-center">
                        <span class="text-xs mr-2">{{ $notification->created_at->diffForHumans(null, true, true) }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            @empty
                <div class="menu-item">
                    <span>No notifications</span>
                </div>
            @endforelse
            <div class="menu-item justify-between">
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-blue-500 hover:underline">Mark all as read</button>
                </form>
                <a href="{{ route('notifications.index') }}" class="text-sm text-blue-500 hover:underline">Show all</a>
            </div>
        </div>
    </div>
</div>