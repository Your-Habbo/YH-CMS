<div class="card p-6">
  <h2 class="text-xl font-semibold mb-6">Settings</h2>
  <nav>
      <ul class="space-y-2">
          <li>
              <a href="{{ route('settings.index') }}" 
                 class="flex items-center p-2 text-gray-700 rounded-lg {{ Route::currentRouteName() == 'settings.index' ? 'bg-blue-100 text-blue-700' : 'hover:bg-gray-100' }} transition-colors duration-150">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  <span class="font-medium">Account</span>
              </a>
          </li>
          <li>
              <a href="{{ route('settings.profile') }}" 
                 class="flex items-center p-2 text-gray-700 rounded-lg {{ Route::currentRouteName() == 'settings.profile' ? 'bg-blue-100 text-blue-700' : 'hover:bg-gray-100' }} transition-colors duration-150">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  <span class="font-medium">Profile</span>
              </a>
          </li>
          <li>
              <a href="{{ route('settings.security.index') }}" 
                 class="flex items-center p-2 text-gray-700 rounded-lg {{ Route::currentRouteName() == 'settings.security.index' ? 'bg-blue-100 text-blue-700' : 'hover:bg-gray-100' }} transition-colors duration-150">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                  <span class="font-medium">Security</span>
              </a>
          </li>
          <li>
              <a href="{{ route('settings.notifications') }}" 
                 class="flex items-center p-2 text-gray-700 rounded-lg {{ Route::currentRouteName() == 'settings.notifications' ? 'bg-blue-100 text-blue-700' : 'hover:bg-gray-100' }} transition-colors duration-150">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                  </svg>
                  <span class="font-medium">Notifications</span>
              </a>
          </li>
      </ul>
  </nav>
</div>