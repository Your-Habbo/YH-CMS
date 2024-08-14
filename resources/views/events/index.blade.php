

    <div class="w-[70%]">
        <!-- Event Banner (Original Style) -->
        <div class="mb-8 rounded-lg overflow-hidden shadow-lg">
            <div class="relative h-64 sm:h-80 md:h-96 bg-gray-800">
                <!-- Featured event content will go here -->
                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="text-center text-white">
                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-2">Featured Event Title</h1>
                        <p class="text-xl sm:text-2xl mb-4">Event Date</p>
                        <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row lg:space-x-8">
            <!-- Main Content (70%) -->
            <div class="w-full lg:w-[70%]">
                <div class="card">
                    <div class="card-header blue">
                        <h2 class="text-xl font-bold text-white">This Week's Events</h2>
                    </div>
                    <div class="card-content p-4">
                        <div class="space-y-6">
                            <!-- Event items will be listed here -->
                            <div class="bg-white rounded-lg shadow-md p-6">
                                <h3 class="text-xl font-semibold">Event Item</h3>
                                <p class="text-gray-600">Event details will go here</p>
                            </div>
                            <!-- More event items... -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar (30%) -->
            <div class="w-full lg:w-[30%] mt-8 lg:mt-0">
                <div class="card">
                    <div class="card-header green">
                        <h3 class="text-xl font-bold text-white">Upcoming Events</h3>
                    </div>
                    <div class="card-content p-4">
                        <!-- Upcoming events list will go here -->
                        <ul class="space-y-4">
                            <li class="text-gray-600">Upcoming event 1</li>
                            <li class="text-gray-600">Upcoming event 2</li>
                            <!-- More upcoming events... -->
                        </ul>
                    </div>
                    <div class="card-footer px-4 py-4">
                        <button class="habbo-button w-full">View All Events</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
