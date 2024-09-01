@pjax('layouts.app')


    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Event Banner with Countdown Timer -->
        <div class="mb-8 rounded-lg overflow-hidden shadow-lg">
            <div class="relative h-64 sm:h-80 md:h-96 bg-gray-800">
                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="text-center text-white">
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-2">Featured Event Title</h1>
                        <p class="text-base sm:text-lg md:text-xl mb-4">Event Date</p>
                        <div id="countdown" class="text-xl mb-4">
                            <span id="days"></span>d
                            <span id="hours"></span>h
                            <span id="minutes"></span>m
                            <span id="seconds"></span>s
                        </div>
                        <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold py-2 px-4 rounded-full transition duration-300">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row lg:space-x-8">
            <!-- Main Content (70%) -->
            <div class="w-full lg:w-[70%]">
                <div class="card bg-white rounded-lg shadow-md">
                    <div class="card-header blue bg-blue-600 p-4">
                        <h2 class="text-lg font-bold text-white">This Week's Events</h2>
                    </div>
                    <div class="card-content p-4">
                        <div class="space-y-6">
                                                        <!-- Day of the week template with improved event card -->
                                                        <div class="day-container">
                                                            <div class="flex items-center mb-4">
                                                                <span class="text-2xs font-bold mr-4">Today</span>
                                                                <div class="flex-grow border-t border-gray-300"></div>
                                                            </div>
                                                            <div class="event-card bg-white rounded-lg shadow-md overflow-hidden">
                                                                <div class="flex flex-col sm:flex-row">
                                                                    <div class="w-full sm:w-1/3 max-w-[150px]">
                                                                        <img src="https://placehold.jp/a7ebec/ffffff/150x150.png" alt="Event image" class="w-full h-full object-cover"/>
                                                                    </div>
                                                                    <div class="w-full sm:w-2/3 p-4">
                                                                        <h4 class="text-base font-semibold mb-2">Event Title</h4>
                                                                        <p class="text-xs text-gray-600 mb-2">Event date and time</p>
                                                                        <p class="text-xs text-gray-700">Event description or additional details go here. This can be a brief overview of what to expect at the event.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                            <!-- Day of the week template with improved event card -->
                            <div class="day-container">
                                <div class="flex items-center mb-4">
                                    <span class="text-2xs font-bold mr-4">Sunday</span>
                                    <div class="flex-grow border-t border-gray-300"></div>
                                </div>
                                <div class="event-card bg-white rounded-lg shadow-md overflow-hidden">
                                    <div class="flex flex-col sm:flex-row">
                                        <div class="w-full sm:w-1/3 max-w-[150px]">
                                            <img src="https://placehold.jp/a7ebec/ffffff/150x150.png" alt="Event image" class="w-full h-full object-cover"/>
                                        </div>
                                        <div class="w-full sm:w-2/3 p-4">
                                            <h4 class="text-base font-semibold mb-2">Event Title</h4>
                                            <p class="text-xs text-gray-600 mb-2">Event date and time</p>
                                            <p class="text-xs text-gray-700">Event description or additional details go here. This can be a brief overview of what to expect at the event.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Repeat for other days -->
                            <div class="day-container">
                                <div class="flex items-center mb-4">
                                    <span class="text-2xs font-bold mr-4">Monday</span>
                                    <div class="flex-grow border-t border-gray-300"></div>
                                </div>
                                <!-- Add event or no-event content here -->
                            </div>
                            
                            <!-- Add similar containers for Tuesday through Saturday -->
                            
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar (30%) -->
            <div class="w-full lg:w-[30%] mt-8 lg:mt-0">
                <div class="card bg-white rounded-lg shadow-md mb-8">
                    <div class="card-header green bg-green-600 p-4">
                        <h3 class="text-lg font-bold text-white">Upcoming Events</h3>
                    </div>
                    <div class="card-content p-4">
                        <ul class="space-y-4">
                            <li class="text-sm text-gray-600">Upcoming event 1</li>
                            <li class="text-sm text-gray-600">Upcoming event 2</li>
                        </ul>
                    </div>
                    <div class="card-footer px-4 py-4">
                        <button class="habbo-button w-full text-sm bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition duration-300">View All Events</button>
                    </div>
                </div>

                <!-- Interactive Calendar -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-semibold mb-4">Calendar</h3>
                    <div id="calendar" class="grid grid-cols-7 gap-1">
                        <!-- Calendar will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Countdown Timer
        function updateCountdown() {
            const eventDate = new Date("2023-12-31T23:59:59").getTime();
            const now = new Date().getTime();
            const distance = eventDate - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("days").textContent = days;
            document.getElementById("hours").textContent = hours;
            document.getElementById("minutes").textContent = minutes;
            document.getElementById("seconds").textContent = seconds;

            if (distance < 0) {
                clearInterval(countdownTimer);
                document.getElementById("countdown").innerHTML = "Event has started!";
            }
        }

        const countdownTimer = setInterval(updateCountdown, 1000);

        // Interactive Calendar
        function generateCalendar() {
            const calendar = document.getElementById('calendar');
            const currentDate = new Date();
            const currentMonth = currentDate.getMonth();
            const currentYear = currentDate.getFullYear();

            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();

            // Add day names
            const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            dayNames.forEach(day => {
                const dayElement = document.createElement('div');
                dayElement.textContent = day;
                dayElement.className = 'text-center font-bold text-xs text-gray-500';
                calendar.appendChild(dayElement);
            });

            // Add empty cells for days before the first day of the month
            for (let i = 0; i < firstDayOfMonth; i++) {
                const emptyDay = document.createElement('div');
                calendar.appendChild(emptyDay);
            }

            // Add calendar days
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.textContent = day;
                dayElement.className = 'text-center py-1 text-sm hover:bg-blue-100 cursor-pointer transition duration-300';
                if (day === currentDate.getDate()) {
                    dayElement.classList.add('bg-blue-500', 'text-white', 'rounded-full');
                }
                calendar.appendChild(dayElement);
            }
        }

        generateCalendar();
    </script>
