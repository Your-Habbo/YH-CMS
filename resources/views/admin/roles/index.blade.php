@pjax('layouts.admin')

<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-800">Roles</h1>
        <a href="{{ route('admin.roles.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
            Create Role
        </a>
    </div>
    
    <div class="mt-6 bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="w-full bg-gray-50 text-left text-gray-500 uppercase tracking-wider">
                    <th class="py-3 px-6 font-medium text-sm">Name</th>
                    <th class="py-3 px-6 font-medium text-sm">Permissions</th>
                    <th class="py-3 px-6 font-medium text-sm">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @foreach($roles as $role)
                    <tr class="border-b border-gray-200">
                        <td class="py-3 px-6">{{ $role->name }}</td>
                        <td class="py-3 px-6">{{ $role->permissions->pluck('name')->join(', ') }}</td>
                        <td class="py-3 px-6 flex items-center space-x-4">
                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="text-yellow-500 hover:text-yellow-700">
                                Edit
                            </a>
                            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

