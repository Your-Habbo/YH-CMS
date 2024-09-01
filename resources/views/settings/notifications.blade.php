@pjax('layouts.app')

<div class="mx-4 max-w-screen-xl sm:mx-8 xl:mx-auto">
  <h1 class="border-b py-6 text-4xl font-semibold">Settings</h1>
  <div class="grid grid-cols-8 pt-3 pb-10 sm:grid-cols-10">
    @include('settings.sidebar')

    <div class="col-span-8 overflow-hidden rounded-xl sm:bg-gray-50 sm:px-8 sm:shadow">
      <div class="pt-4">
        <h1 class="py-2 text-2xl font-semibold">Notification Settings</h1>
        <p class="text-slate-600">Manage your notification preferences.</p>
      </div>
      <hr class="mt-4 mb-8" />

      <!-- Notification content goes here -->
      <p>Notification settings content...</p>

    </div>
  </div>
</div>
