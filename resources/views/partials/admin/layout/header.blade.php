<header class="header d-flex justify-content-between align-items-center ms-2">
    <div class="d-flex align-items-center">
        <!-- Desktop Sidebar Toggle -->
        <button class="toggle-btn me-3 d-none d-md-block" @click="sidebarCollapsed = !sidebarCollapsed"
            title="Toggle Sidebar">
            <i class="bi bi-list"></i>
        </button>

        <!-- Mobile Menu Toggle -->
        <button class="toggle-btn me-3 d-md-none" @click="showSidebar = !showSidebar" title="Toggle Mobile Menu">
            <i class="bi bi-menu-button-wide"></i>
        </button>

        <div>
            <h4 class="mb-0">{{ $pageTitle ?? 'Dashboard' }}</h4>
            @isset($breadcrumbs)
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        @foreach ($breadcrumbs as $breadcrumb)
                            @if ($loop->last)
                                <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['title'] }}</li>
                            @else
                                <li class="breadcrumb-item">
                                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            @endisset
        </div>
    </div>

    <div class="d-flex align-items-center">
        <!-- Notifications -->
        <div class="dropdown me-3">
            <button class="btn btn-outline-secondary position-relative" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    3
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <h6 class="dropdown-header">Notifications</h6>
                </li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-info-circle me-2"></i>New user
                        registered</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-cart me-2"></i>New order received</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-exclamation-triangle me-2"></i>System
                        alert</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-center" href="#">View all notifications</a></li>
            </ul>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-2"></i>{{ auth()->user()->name ?? 'Admin' }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-person me-2"></i>Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-gear me-2"></i>Settings
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

{{-- <header class="header">
    <div class="d-flex align-items-center gap-4">
        <button class="btn btn-outline-primary-hover" @click="sidebarOpen = !sidebarOpen">
            <i class="bi bi-list text-dark fs-5"></i>
        </button>
    </div>
    <x-popover placement="bottom" triggerClass="cursor-pointer">
        <x-slot name="trigger">
            <i class="bi bi-person-circle fs-5 text-dark"></i>
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
</header> --}}
