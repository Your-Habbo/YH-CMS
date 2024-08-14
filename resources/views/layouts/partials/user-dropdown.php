<div class="relative dropdown">
    <a href="#" class="nav-text hover:text-gray-300 drop-shadow-2xl uppercase">{{ auth()->user()->name }}</a>
    <div class="dropdown-menu drop-shadow-2xl">
        <a href="{{ route('profile.edit') }}" class="menu-item">
            <span>Profile</span>
            <img src="/assets/img/right-arrow.svg" alt="Right Arrow" style="height: 28px; width:23px;">
        </a>
        <a href="{{ route('settings') }}" class="menu-item">
            <span>Settings</span>
            <img src="/assets/img/right-arrow.svg" alt="Right Arrow" style="height: 28px; width:23px;">
        </a>
        <form method="POST" action="{{ route('logout') }}" class="menu-item">
            @csrf
            <button type="submit" class="w-full text-left flex justify-between items-center">
                <span>Logout</span>
                <img src="/assets/img/right-arrow.svg" alt="Right Arrow" style="height: 28px; width:23px;">
            </button>
        </form>
    </div>
</div>