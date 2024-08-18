@extends('admin.layouts.app')

@section('content')
<main class="flex-1 overflow-y-auto bg-gray-100">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold mb-6">Forum Activity Dashboard</h1>

        <!-- Top Widgets -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow text-center">
                <h3 class="text-lg font-medium text-gray-700">Posts Today</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $postsToday }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow text-center">
                <h3 class="text-lg font-medium text-gray-700">Posts This Week</h3>
                <p class="text-3xl font-bold text-green-600">{{ $postsThisWeek }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow text-center">
                <h3 class="text-lg font-medium text-gray-700">Posts This Month</h3>
                <p class="text-3xl font-bold text-red-600">{{ $postsThisMonth }}</p>
            </div>
        </div>

        <!-- Main Graphs Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Threads Per Day -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-medium text-gray-700 mb-4">Threads Created Per Day (Last 30 Days)</h2>
                <canvas id="threadsPerDayChart"></canvas>
            </div>

            <!-- Posts Per Day -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-medium text-gray-700 mb-4">Posts Created Per Day (Last 30 Days)</h2>
                <canvas id="postsPerDayChart"></canvas>
            </div>

            <!-- Likes Per Day -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-medium text-gray-700 mb-4">Likes Received Per Day (Last 30 Days)</h2>
                <canvas id="likesPerDayChart"></canvas>
            </div>

            <!-- Daily Active Users -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-medium text-gray-700 mb-4">Daily Active Users (Last 30 Days)</h2>
                <canvas id="activeUsersPerDayChart"></canvas>
            </div>
        </div>

        <!-- Category Activity -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-medium text-gray-700 mb-4">Most Active Categories (Threads and Posts)</h2>
            <canvas id="categoryActivityChart"></canvas>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Threads Per Day
    const threadsPerDayData = {
        labels: {!! json_encode($threadsPerDay->keys()) !!},
        datasets: [{
            label: 'Threads Created',
            data: {!! json_encode($threadsPerDay->values()) !!},
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderWidth: 1,
            fill: true,
            tension: 0.4
        }]
    };
    const threadsPerDayChart = new Chart(document.getElementById('threadsPerDayChart'), {
        type: 'line',
        data: threadsPerDayData,
        options: {
            scales: {
                x: { title: { display: true, text: 'Date' }},
                y: { title: { display: true, text: 'Threads' }}
            }
        }
    });

    // Posts Per Day
    const postsPerDayData = {
        labels: {!! json_encode($postsPerDay->keys()) !!},
        datasets: [{
            label: 'Posts Created',
            data: {!! json_encode($postsPerDay->values()) !!},
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderWidth: 1,
            fill: true,
            tension: 0.4
        }]
    };
    const postsPerDayChart = new Chart(document.getElementById('postsPerDayChart'), {
        type: 'line',
        data: postsPerDayData,
        options: {
            scales: {
                x: { title: { display: true, text: 'Date' }},
                y: { title: { display: true, text: 'Posts' }}
            }
        }
    });

    // Likes Per Day
    const likesPerDayData = {
        labels: {!! json_encode($likesPerDay->keys()) !!},
        datasets: [{
            label: 'Likes Received',
            data: {!! json_encode($likesPerDay->values()) !!},
            borderColor: 'rgba(153, 102, 255, 1)',
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderWidth: 1,
            fill: true,
            tension: 0.4
        }]
    };
    const likesPerDayChart = new Chart(document.getElementById('likesPerDayChart'), {
        type: 'line',
        data: likesPerDayData,
        options: {
            scales: {
                x: { title: { display: true, text: 'Date' }},
                y: { title: { display: true, text: 'Likes' }}
            }
        }
    });

    // Daily Active Users
    const activeUsersPerDayData = {
        labels: {!! json_encode($activeUsersPerDay->keys()) !!},
        datasets: [{
            label: 'Active Users',
            data: {!! json_encode($activeUsersPerDay->values()) !!},
            borderColor: 'rgba(255, 159, 64, 1)',
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            borderWidth: 1,
            fill: true,
            tension: 0.4
        }]
    };
    const activeUsersPerDayChart = new Chart(document.getElementById('activeUsersPerDayChart'), {
        type: 'line',
        data: activeUsersPerDayData,
        options: {
            scales: {
                x: { title: { display: true, text: 'Date' }},
                y: { title: { display: true, text: 'Users' }}
            }
        }
    });

    // Category Activity
    const categoryActivityData = {
        labels: {!! json_encode($categoryActivity->pluck('name')) !!},
        datasets: [{
            label: 'Threads',
            data: {!! json_encode($categoryActivity->pluck('thread_count')) !!},
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }, {
            label: 'Posts',
            data: {!! json_encode($categoryActivity->pluck('post_count')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };
    const categoryActivityChart = new Chart(document.getElementById('categoryActivityChart'), {
        type: 'bar',
        data: categoryActivityData,
        options: {
            scales: {
                x: { title: { display: true, text: 'Category' }},
                y: { title: { display: true, text: 'Count' }}
            }
        }
    });
</script>
@endsection
