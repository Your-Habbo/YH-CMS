@pjax('layouts.admin')

<!-- Page Content -->
<main class="flex-1 overflow-y-auto bg-gray-100">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Main content area -->
        <div class="container mx-auto px-4 sm:px-8">
            <div class="py-8">
                <h2 class="text-2xl font-semibold leading-tight mb-4">Edit User: {{ $user->username }}</h2>
                
                <div x-data="{ activeTab: 'basic' }" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <!-- Tabs -->
                    <div class="flex border-b mb-4">
                        <button @click="activeTab = 'basic'" :class="{'border-b-2 border-blue-500': activeTab === 'basic'}" class="mr-4 py-2 px-4 text-sm font-medium">Basic Info</button>
                        <button @click="activeTab = 'security'" :class="{'border-b-2 border-blue-500': activeTab === 'security'}" class="mr-4 py-2 px-4 text-sm font-medium">Security</button>
                        <button @click="activeTab = 'habbo'" :class="{'border-b-2 border-blue-500': activeTab === 'habbo'}" class="mr-4 py-2 px-4 text-sm font-medium">Habbo Profile</button>
                        <button @click="activeTab = 'radio'" :class="{'border-b-2 border-blue-500': activeTab === 'radio'}" class="mr-4 py-2 px-4 text-sm font-medium">Radio Settings</button>
                        <button @click="activeTab = 'activity'" :class="{'border-b-2 border-blue-500': activeTab === 'activity'}" class="mr-4 py-2 px-4 text-sm font-medium">Activity Log</button>
                        <button @click="activeTab = 'roles'" :class="{'border-b-2 border-blue-500': activeTab === 'roles'}" class="py-2 px-4 text-sm font-medium">Roles</button>
                    </div>
        
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div x-show="activeTab === 'basic'">
                            <h3 class="text-lg font-semibold mb-2">Basic Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" name="username" type="text" value="{{ old('username', $user->username) }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" type="email" value="{{ old('email', $user->email) }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" value="{{ old('name', $user->name) }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="dob">Date of Birth</label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="dob" name="dob" type="date" value="{{ old('dob', $user->dob ? $user->dob->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Security Settings -->
                        <div x-show="activeTab === 'security'">
                            <h3 class="text-lg font-semibold mb-2">Security Settings</h3>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">New Password</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" type="password">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">Confirm Password</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password_confirmation" name="password_confirmation" type="password">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="ban_status">Ban Status</label>
                                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="ban_status" name="ban_status">
                                    <option value="not_banned">Not Banned</option>
                                    <option value="temp_banned">Temporarily Banned</option>
                                    <option value="permanently_banned">Permanently Banned</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="ban_duration">Ban Duration (days)</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="ban_duration" name="ban_duration" type="number">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="ban_reason">Ban Reason</label>
                                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="ban_reason" name="ban_reason" rows="3"></textarea>
                            </div>
                            <div class="flex items-center mb-2">
                                <input type="checkbox" id="mfa_enabled" name="mfa_enabled" class="mr-2">
                                <label for="mfa_enabled" class="text-gray-700 text-sm font-bold">Enable Multi-Factor Authentication</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="force_password_reset" name="force_password_reset" class="mr-2">
                                <label for="force_password_reset" class="text-gray-700 text-sm font-bold">Force Password Reset on Next Login</label>
                            </div>
                        </div>

                        <!-- Habbo Profile -->
                        <div x-show="activeTab === 'habbo'">
                            <h3 class="text-lg font-semibold mb-2">Habbo Profile</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="habbo_username">Habbo Username</label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="habbo_username" name="habbo_username" type="text" value="{{ old('habbo_username', $user->habbo_username) }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="favorite_room">Favorite Room</label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="favorite_room" name="favorite_room" type="text" value="{{ old('favorite_room', $user->favorite_room) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Radio DJ Settings -->
                        <div x-show="activeTab === 'radio'">
                            <h3 class="text-lg font-semibold mb-2">DJ Settings</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="dj_name">DJ Name</label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="dj_name" name="dj_name" type="text" value="{{ old('dj_name', $user->dj_name) }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="music_genre">Preferred Music Genre</label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="music_genre" name="music_genre" type="text" value="{{ old('music_genre', $user->music_genre) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Activity Logs -->
                        <div x-show="activeTab === 'activity'">
                            <h3 class="text-lg font-semibold mb-4">Activity Logs</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full leading-normal">
                                    <thead>
                                        <tr>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Date
                                            </th>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Action
                                            </th>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                IP Address
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($activityLogs as $log)
                                        <tr>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                {{ $log->description }}
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                {{ $log->properties['ip'] ?? 'N/A' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Roles Management -->
                        <div x-show="activeTab === 'roles'">
                            <h3 class="text-lg font-semibold mb-2">Manage Roles</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($roles as $role)
                                <div class="flex items-center">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}" {{ in_array($role->name, $userRoles) ? 'checked' : '' }} class="mr-2">
                                    <label for="role_{{ $role->id }}" class="text-gray-700">{{ $role->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                Update User
                            </button>
                            <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="{{ route('admin.users.index') }}">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
