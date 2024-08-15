<!-- layouts/partials/nav.blade.php -->
<nav x-data="{ mobileMenuOpen: false }">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Logo or Brand -->
            <a href="/" class="nav-text text-xl font-bold">YourHabbo</a>

            <!-- Mobile menu button -->
            <div class="flex md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="nav-text hover:text-gray-300 focus:outline-none focus:text-gray-300">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex justify-center items-center h-20 relative">
                {!! App\Menu\MainMenu::build() !!}
            </div>

            <!-- Right side: User Options (visible on desktop) -->
            <div class="hidden md:flex items-center space-x-4 text-sm">
                @auth
                    <div class="flex items-center space-x-4">
                        <!-- Habbo Head -->
                        <div class="w-8 h-8 rounded-full overflow-hidden">
                            <img src="{{ auth()->user()->getHabboHeadUrl() }}" alt="{{ auth()->user()->name }}'s Habbo" class="w-full h-full object-cover">
                        </div>

                        <!-- Notifications Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <!-- ... (keep your existing notifications dropdown code) ... -->
                        </div>

                        <!-- User Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <!-- ... (keep your existing user dropdown code) ... -->
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-text hover:text-gray-300 drop-shadow-2xl uppercase">Login</a>
                    <a href="{{ route('register') }}" class="nav-text hover:text-gray-300 drop-shadow-2xl uppercase">Register</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            {!! App\Menu\MainMenu::buildMobile() !!}
        </div>
        @auth
            <div class="pt-4 pb-3 border-t border-gray-700">
                <div class="flex items-center px-5">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" src="{{ auth()->user()->getHabboHeadUrl() }}" alt="{{ auth()->user()->name }}'s Habbo">
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium leading-none nav-text">{{ auth()->user()->name }}</div>
                    </div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <a href="{{ route('profile.show', auth()->user()) }}" class="block px-3 py-2 rounded-md text-base font-medium nav-text hover:text-gray-300">Profile</a>
                    <a href="{{ route('settings.index') }}" class="block px-3 py-2 rounded-md text-base font-medium nav-text hover:text-gray-300">Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium nav-text hover:text-gray-300">Logout</button>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-3 border-t border-gray-700">
                <div class="flex items-center px-5">
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium nav-text hover:text-gray-300">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium nav-text hover:text-gray-300">Register</a>
                </div>
            </div>
        @endauth
    </div>
</nav>