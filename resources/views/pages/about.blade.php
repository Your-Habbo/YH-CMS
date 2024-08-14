
<main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-center mb-8">About HabboFanHub</h1>

        <!-- Mission Statement -->
        <div class="card mb-8">
            <div class="card-header blue">
                <h2 class="text-xl font-bold text-white">Our Mission</h2>
            </div>
            <div class="card-content p-6">
                <p class="text-lg mb-4">
                    HabboFanHub is dedicated to bringing together Habbo enthusiasts from around the world. Our mission is to create a vibrant community where fans can share their passion, creativity, and experiences related to the Habbo universe.
                </p>
                <p class="text-lg">
                    We strive to provide a platform for fans to connect, collaborate, and celebrate the unique world of Habbo, fostering friendship and creativity in a safe and welcoming environment.
                </p>
            </div>
        </div>

        <!-- Our Story -->
        <div class="card mb-8">
            <div class="card-header green">
                <h2 class="text-xl font-bold text-white">Our Story</h2>
            </div>
            <div class="card-content p-6">
                <p class="mb-4">
                    Founded in 2023 by a group of passionate Habbo players, HabboFanHub started as a small forum for sharing room designs and Habbo tips. As our community grew, so did our vision.
                </p>
                <p class="mb-4">
                    Today, we're proud to offer a comprehensive platform that includes forums, events, custom radio stations, and much more. Our journey is a testament to the power of community and shared passion.
                </p>
                <p>
                    From our humble beginnings to where we are now, our love for Habbo and its community has been the driving force behind everything we do.
                </p>
            </div>
        </div>

        <!-- Meet the Team -->
        <div class="card mb-8">
            <div class="card-header orange">
                <h2 class="text-xl font-bold text-white">Meet the Team</h2>
            </div>
            <div class="card-content p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Team Member 1 -->
                    <div class="text-center">
                        <img src="{{ asset('images/team/member1.png') }}" alt="HabboUser123" class="w-32 h-32 rounded-full mx-auto mb-4">
                        <h3 class="text-lg font-semibold">HabboUser123</h3>
                        <p class="text-gray-600">Founder & Lead Developer</p>
                        <p class="mt-2">Habbo veteran since 2006, coding enthusiast, and community builder.</p>
                    </div>
                    <!-- Team Member 2 -->
                    <div class="text-center">
                        <img src="{{ asset('images/team/member2.png') }}" alt="PixelQueen" class="w-32 h-32 rounded-full mx-auto mb-4">
                        <h3 class="text-lg font-semibold">PixelQueen</h3>
                        <p class="text-gray-600">Creative Director & Event Organizer</p>
                        <p class="mt-2">Room design expert, event planner extraordinaire, and Habbo artist.</p>
                    </div>
                    <!-- Add more team members as needed -->
                </div>
            </div>
        </div>

        <!-- Community Values -->
        <div class="card">
            <div class="card-header purple">
                <h2 class="text-xl font-bold text-white">Our Community Values</h2>
            </div>
            <div class="card-content p-6">
                <ul class="list-disc pl-6 space-y-2">
                    <li>Inclusivity: We welcome all Habbo fans, regardless of experience level or background.</li>
                    <li>Creativity: We encourage and celebrate the unique ideas and creations of our members.</li>
                    <li>Respect: We foster a supportive environment where everyone's voice is valued.</li>
                    <li>Fun: Above all, we're here to enjoy and share our love for the Habbo world.</li>
                    <li>Safety: We prioritize creating a safe space for all our community members.</li>
                </ul>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center mt-8">
            <p class="text-lg mb-4">Want to be part of our amazing community?</p>
            <a href="{{ route('register') }}" class="habbo-button">Join HabboFanHub Today!</a>
        </div>
    </div>
</main>
