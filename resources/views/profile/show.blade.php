
<main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Profile Header -->
        <div class="card mb-6">
            <div class="card-content p-6">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="md:w-1/3 text-center md:text-left mb-4 md:mb-0">
                        <img src="{{ asset('path/to/avatar.png') }}" alt="{{ $user->name }}'s Avatar" class="w-32 h-32 rounded-full mx-auto md:mx-0">
                    </div>
         
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row lg:space-x-6">
            <!-- Left Column -->
            <div class="w-full lg:w-2/3">
                <!-- About Me -->
                <div class="card mb-6">
                    <div class="card-header blue">
                        <h2 class="text-xl font-bold text-white">About Me</h2>
                    </div>
                    <div class="card-content p-6">
                        <p>{{ $user->bio ?? 'This user hasn\'t written a bio yet.' }}</p>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="card mb-6">
                    <div class="card-header green">
                        <h2 class="text-xl font-bold text-white">Recent Activities</h2>
                    </div>
                    <div class="card-content p-6">
                        <ul class="space-y-4">
                            <!-- Sample activities, replace with actual data -->
                            <li class="flex items-center">
                                <span class="mr-2">🏆</span>
                                <span>Won the weekly trivia contest</span>
                            </li>
                            <li class="flex items-center">
                                <span class="mr-2">💬</span>
                                <span>Posted a new forum thread: "Best Habbo Room Designs"</span>
                            </li>
                            <li class="flex items-center">
                                <span class="mr-2">🎉</span>
                                <span>Attended the Summer Beach Party event</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- User's Posts or Comments -->
                <div class="card">
                    <div class="card-header orange">
                        <h2 class="text-xl font-bold text-white">Recent Posts</h2>
                    </div>
                    <div class="card-content p-6">
                        <!-- Sample posts, replace with actual data -->
                        <div class="space-y-4">
                            <div class="border-b pb-4">
                                <h3 class="font-semibold mb-2">Tips for New Habbo Players</h3>
                                <p class="text-gray-600">Here are some tips for those just starting out in Habbo...</p>
                            </div>
                            <div class="border-b pb-4">
                                <h3 class="font-semibold mb-2">My Favorite Habbo Memories</h3>
                                <p class="text-gray-600">I've been playing Habbo for years, and here are some of my favorite moments...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="w-full lg:w-1/3 mt-6 lg:mt-0">
                <!-- User Stats -->
                <div class="card mb-6">
                    <div class="card-header purple">
                        <h2 class="text-xl font-bold text-white">Stats</h2>
                    </div>
                    <div class="card-content p-6">
                        <ul class="space-y-2">
                            <li class="flex justify-between">
                                <span>Forum Posts:</span>
                                <span class="font-semibold">127</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Events Attended:</span>
                                <span class="font-semibold">15</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Friends:</span>
                                <span class="font-semibold">42</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Badges or Achievements -->
                <div class="card mb-6">
                    <div class="card-header red">
                        <h2 class="text-xl font-bold text-white">Badges</h2>
                    </div>
                    <div class="card-content p-6">
                        <div class="grid grid-cols-3 gap-4">
                            <!-- Sample badges, replace with actual data -->
                            <img src="{{ asset('path/to/badge1.png') }}" alt="Event Organizer" class="w-full">
                            <img src="{{ asset('path/to/badge2.png') }}" alt="Forum Superstar" class="w-full">
                            <img src="{{ asset('path/to/badge3.png') }}" alt="Habbo Veteran" class="w-full">
                        </div>
                    </div>
                </div>

                <!-- Friends List -->
                <div class="card">
                    <div class="card-header teal">
                        <h2 class="text-xl font-bold text-white">Friends</h2>
                    </div>
                    <div class="card-content p-6">
                        <div class="grid grid-cols-3 gap-4">
                            <!-- Sample friends, replace with actual data -->
                            <div class="text-center">
                                <img src="{{ asset('path/to/friend1.png') }}" alt="Friend 1" class="w-16 h-16 rounded-full mx-auto mb-2">
                                <p class="text-sm">CoolHabbo1</p>
                            </div>
                            <div class="text-center">
                                <img src="{{ asset('path/to/friend2.png') }}" alt="Friend 2" class="w-16 h-16 rounded-full mx-auto mb-2">
                                <p class="text-sm">HabboFan42</p>
                            </div>
                            <div class="text-center">
                                <img src="{{ asset('path/to/friend3.png') }}" alt="Friend 3" class="w-16 h-16 rounded-full mx-auto mb-2">
                                <p class="text-sm">PartyQueen</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
