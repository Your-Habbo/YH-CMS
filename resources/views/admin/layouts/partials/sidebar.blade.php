<aside class="bg-gray-800 text-white w-64 flex flex-col">
            <div class="p-4">
                <h1 class="text-2xl font-bold">Habbo Radio Admin</h1>
            </div>
            {!! App\Menu\AdminMenu::build() !!}
            <div class="p-4">
                <button class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded flex items-center justify-center">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </div>
        </aside>