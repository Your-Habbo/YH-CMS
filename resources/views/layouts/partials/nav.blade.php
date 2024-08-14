<!-- layouts/partials/nav.blade.php -->
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center h-16">
        <!-- Logo or Brand -->
        <a href="/" class="nav-text text-xl font-bold">YourHabbo</a>

        <!-- Center: Navigation links -->
        <div class="flex justify-center items-center h-20 relative">
            {!! App\Menu\MainMenu::build() !!}
        </div>

        <!-- Right side: User Options -->
        <div class="flex items-center space-x-4 text-sm">
            @auth
                <div class="flex items-center space-x-4">
                    <!-- Habbo Head -->
                    <div class="w-8 h-8 rounded-full overflow-hidden">
                        <img src="{{ auth()->user()->getHabboHeadUrl() }}" alt="{{ auth()->user()->name }}'s Habbo" class="w-full h-full object-cover">
                    </div>

                    <!-- Notifications Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center text-white hover:text-gray-300">
                            <span class="relative nav-text">
                                <svg class="w-5 h-5 nav-text " fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{ auth()->user()->unreadNotifications->count() }}</span>
                                @endif
                            </span>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-md overflow-hidden shadow-xl z-50">
                            <div class="px-4 py-2 bg-gray-100 text-gray-800 flex justify-between items-center">
                                <h3 >Notifications</h3>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">Mark all as read</button>
                                    </form>
                                @endif
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                    <a href="#" class="block px-4 py-3 border-b hover:bg-gray-50 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                                        <p class="text-sm text-gray-700">{{ $notification->data['message'] }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </a>
                                @empty
                                    <p class="px-4 py-3 text-sm text-gray-500">No notifications</p>
                                @endforelse
                            </div>
                            @if(auth()->user()->notifications->count() > 5)
                                <a href="{{ route('notifications.index') }}" class="block bg-gray-100 text-center py-2 text-sm text-gray-800 hover:bg-gray-200">View all notifications</a>
                            @endif
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center text-white hover:text-gray-300">
                            <span>{{ auth()->user()->name }}</span>
                            <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}" class="inline w-4 h-4 ml-1 transition-transform duration-200 transform">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-50">
                            <a href="{{ route('profile.show', auth()->user()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav-text hover:text-gray-300 drop-shadow-2xl uppercase">Login</a>
                <a href="{{ route('register') }}" class="nav-text hover:text-gray-300 drop-shadow-2xl uppercase">Register</a>
            @endauth
        </div>
    </div>
</div>