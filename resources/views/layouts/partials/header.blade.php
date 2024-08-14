<header class="relative">
    <nav class="bg-[#4c4c4a] text-white nav relative z-50">
        @include('layouts.partials.nav')
    </nav>

    <div class="h-1 bg-[#3a3a3a]"></div>
    <div class="h-px bg-gray-600 shadow-2xl"></div>

    <!-- Your main content goes here -->
    <div class="bg-[#8aaec6] h-60 shadow-inner relative overflow-hidden z-10">
            <!-- Clouds behind hotel -->
            <svg class="cloud cloud-back cloud1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 70">
            <path fill="#fff" stroke="#1a1a1a" stroke-width="1" d="M625 316 c-36 -16 -82 -67 -91 -102 -4 -14 -13 -24 -24 -24 -19 0
-50 -30 -50 -48 0 -7 -16 -12 -41 -12 -31 0 -47 -6 -70 -29 -35 -35 -37 -64
-7 -85 19 -13 61 -16 253 -16 192 0 234 3 253 16 29 20 28 29 -8 74 -27 34
-29 41 -19 63 7 15 9 41 5 60 -17 91 -115 141 -201 103z m120 -10 c65 -27 93
-109 57 -169 -19 -33 -19 -34 -1 -30 24 7 36 -22 18 -45 -13 -15 -12 -16 3 -4
19 15 38 8 38 -13 0 -30 -40 -35 -265 -35 -235 0 -265 5 -265 41 0 36 44 69
93 69 23 0 49 -6 56 -13 11 -9 10 -7 0 11 -26 42 29 82 69 50 15 -12 16 -11 3
4 -16 22 -9 55 20 93 41 51 110 67 174 41z"/>
<path fill="#fff" stroke="#1a1a1a" d="M52 104 c-12 -8 -22 -20 -22 -26 0 -6 -7 -20 -16 -30 -28 -30 -2 -48
70 -48 93 0 107 23 52 83 -37 41 -51 44 -84 21z m63 -8 c15 -11 17 -17 7 -28
-9 -12 -8 -14 11 -14 16 1 22 -5 22 -19 0 -18 -8 -20 -64 -23 -69 -3 -92 7
-71 32 7 9 16 13 21 10 5 -3 6 3 2 12 -4 11 1 22 12 30 24 18 36 18 60 0z"/>
            </svg>
            <svg class="cloud cloud-back cloud2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 70">
                <path fill="#fff" stroke="#1a1a1a" stroke-width="1" d="M47.2,5.9c-0.1-2.5-2.2-4.5-4.7-4.5c-1.2,0-2.2,0.4-3,1.1c-0.7-3-3.4-5.2-6.5-5.2c-3.7,0-6.7,3-6.7,6.7c0,0.3,0,0.6,0.1,0.9c-0.6-0.2-1.2-0.3-1.9-0.3c-3.2,0-5.8,2.6-5.8,5.8c0,3.2,2.6,5.8,5.8,5.8h21.3c2.9,0,5.3-2.4,5.3-5.3C52.1,8.5,50,6.3,47.2,5.9z"/>
            </svg>
            
            <!-- Hotel image -->
            <img src="{{ asset('assets/img/hotel.png') }}" alt="Habbo Hotel" class="hotel-image">
            
            <!-- Clouds in front of hotel -->
            <svg class="cloud cloud-front cloud3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 70">
                <path fill="#fff" stroke="#1a1a1a" stroke-width="1" d="M47.2,5.9c-0.1-2.5-2.2-4.5-4.7-4.5c-1.2,0-2.2,0.4-3,1.1c-0.7-3-3.4-5.2-6.5-5.2c-3.7,0-6.7,3-6.7,6.7c0,0.3,0,0.6,0.1,0.9c-0.6-0.2-1.2-0.3-1.9-0.3c-3.2,0-5.8,2.6-5.8,5.8c0,3.2,2.6,5.8,5.8,5.8h21.3c2.9,0,5.3-2.4,5.3-5.3C52.1,8.5,50,6.3,47.2,5.9z"/>
            </svg>
            <svg class="cloud cloud-front cloud4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 60">
                <path fill="#fff" stroke="#1a1a1a" stroke-width="1" d="M47.2,5.9c-0.1-2.5-2.2-4.5-4.7-4.5c-1.2,0-2.2,0.4-3,1.1c-0.7-3-3.4-5.2-6.5-5.2c-3.7,0-6.7,3-6.7,6.7c0,0.3,0,0.6,0.1,0.9c-0.6-0.2-1.2-0.3-1.9-0.3c-3.2,0-5.8,2.6-5.8,5.8c0,3.2,2.6,5.8,5.8,5.8h21.3c2.9,0,5.3-2.4,5.3-5.3C52.1,8.5,50,6.3,47.2,5.9z"/>
            </svg>
            <svg class="cloud cloud-front cloud5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 60">
                <path fill="#fff" stroke="#1a1a1a" stroke-width="1" d="M47.2,5.9c-0.1-2.5-2.2-4.5-4.7-4.5c-1.2,0-2.2,0.4-3,1.1c-0.7-3-3.4-5.2-6.5-5.2c-3.7,0-6.7,3-6.7,6.7c0,0.3,0,0.6,0.1,0.9c-0.6-0.2-1.2-0.3-1.9-0.3c-3.2,0-5.8,2.6-5.8,5.8c0,3.2,2.6,5.8,5.8,5.8h21.3c2.9,0,5.3-2.4,5.3-5.3C52.1,8.5,50,6.3,47.2,5.9z"/>
            </svg>
    
    

        </div>
        
</header>
<div class="h-1 bg-white shadow-2xl"></div>
<div class="h-0.5 bg-[#c2c2c2] shadow-2xl"></div>