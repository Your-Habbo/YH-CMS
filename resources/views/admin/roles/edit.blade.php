@pjax('layouts.admin')

<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Role: {{ $role->name }}</h1>

    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-medium mb-2">Role Name</label>
            <input type="text" name="name" id="name" class="form-input w-full rounded-md shadow-sm" value="{{ $role->name }}" readonly>
        </div>

        <div class="mb-6">
            <label for="permissions" class="block text-gray-700 font-medium mb-2">Assign Permissions</label>
            <div class="grid grid-cols-2 gap-4">
                @foreach($permissions as $permission)
                    <div class="flex items-center">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission-{{ $permission->id }}"
                               class="form-checkbox h-4 w-4 text-blue-600" {{ $role->permissions->contains($permission) ? 'checked' : '' }}>
                        <label for="permission-{{ $permission->id }}" class="ml-2">{{ $permission->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
            Update Role
        </button>
    </form>
</div>

