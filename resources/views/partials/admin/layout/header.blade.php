<header class="navbar bg-white rounded-3 shadow-sm px-3 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-4">
        <button class="btn btn-outline-primary-hover" @click="sidebarOpen = !sidebarOpen">
            <i class="bi bi-list text-dark"></i>
        </button>
    </div>
    <x-popover placement="bottom" triggerClass="cursor-pointer">
        <x-slot name="trigger">
            <i class="bi bi-person-circle"></i>
        </x-slot>

        <div class="flex flex-col">
            <a href="/profile" class="dropdown-item">Profile</a>
            <a href="/settings" class="dropdown-item">Settings</a>
            <hr>
            <form method="POST" action="{{ route('logout') }}" class="w-full dropdown-item">
                @csrf
                <button as="button" type="submit" icon="arrow-right-start-on-rectangle"
                    class="btn btn-danger btn-sm w-100">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </x-popover>
</header>
