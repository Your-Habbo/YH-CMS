@pjax('layouts.app')

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Notifications</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <li class="px-4 py-4 sm:px-6 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                    <div class="flex items-center justify-between">
                        <div class="sm:flex sm:justify-between sm:w-full">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $notification->data['message'] }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if(!$notification->read_at)
                                <div class="mt-2 sm:mt-0">
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">Mark as read</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-4 sm:px-6">
                    <p class="text-sm text-gray-500">No notifications</p>
                </li>
            @endforelse
        </ul>
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
