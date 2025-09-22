<div class="sidebar " :class="{ 'collapsed': sidebarCollapsed, 'show': showSidebar }">
    <div class=" d-flex flex-column align-items-center w-100 rounded-lg h-100 px-2 py-2">
        <a href="{{ route('dashboard') }}">
            <div class="oveflow-hidden d-flex align-items-center justify-content-center"
                style="height: 50px; width: 100px;">
                <img style="object-fit: cover; height: 100%; width: auto" src="{{ asset('assets/img/pps.png') }}"
                    alt="">
            </div>
        </a>

        <ul class="sidebar-nav">
            <li class="nav-item">
                <a wire:navigate href="{{ route('dashboard') }}"
                    class="sidebar-nav-link rounded-1 d-flex align-items-center gap-1 {{ request()->routeIs('dashboard') ? 'bg-primary text-light' : 'bg-white text-dark' }}"
                    :class="{ 'justify-content-center': sidebarCollapsed }">
                    <i
                        class="bi bi-speedometer2 {{ request()->routeIs('dashboard') ? 'text-light' : 'text-dark' }}"></i>
                    <span class="fs-6" x-show="!sidebarCollapsed">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a wire:navigate href="{{ route('admin.posts.index') }}"
                    class="sidebar-nav-link text-dark rounded-1 d-flex align-items-center gap-1 {{ request()->routeIs('admin.posts.*') ? 'bg-primary text-light' : 'bg-white text-dark' }}"
                    :class="{ 'justify-content-center': sidebarCollapsed }">
                    <i class="bi bi-book {{ request()->routeIs('admin.posts.*') ? 'text-light' : 'text-dark' }}"></i>
                    <span x-show="!sidebarCollapsed">Berita</span>
                </a>
            </li>

            <li class="sidebar-nav-item">
                <a wire:navigate href="{{ route('admin.kegiatan.index') }}"
                    class="sidebar-nav-link text-dark rounded-1 d-flex align-items-center gap-1 {{ request()->routeIs('admin.kegiatan.*') ? 'bg-primary text-light' : 'bg-white text-dark' }}"
                    :class="{ 'justify-content-center': sidebarCollapsed }">
                    <i
                        class="bi bi-clipboard-check {{ request()->routeIs('admin.kegiatan.*') ? 'text-light' : 'text-dark' }}"></i>
                    <span x-show="!sidebarCollapsed">Kegiatan</span>
                </a>
            </li>
        </ul>
    </div>
</div>

{{-- <aside class="sidebar" x-cloak :class="{ 'collapsed': !sidebarOpen }" style="transition: all 0.3s ease;">
    <div class="sidebar-content">
        <div class="py-2">
            <h2 class="fs-5 text-center mb-4">BAPPERIDA PPS</h2>
        </div>
        <nav class="nav flex-column">
            <ul class="nav nav-pils flex-column">
                <li class="nav-item">
                    <a wire:navigate href="{{ route('dashboard') }}"
                        class="nav-link rounded-1 d-flex align-items-center gap-1 {{ request()->routeIs('dashboard') ? 'bg-primary text-light' : 'bg-white text-dark' }}">
                        <i
                            class="bi bi-speedometer2 {{ request()->routeIs('dashboard') ? 'text-light' : 'text-dark' }}"></i>
                        <span class="fs-6">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a wire:navigate href="{{ route('admin.posts.index') }}"
                        class="nav-link text-dark rounded-1 d-flex align-items-center gap-1 {{ request()->routeIs('admin.posts.*') ? 'bg-primary text-light' : 'bg-white text-dark' }}">
                        <i
                            class="bi bi-book {{ request()->routeIs('admin.posts.*') ? 'text-light' : 'text-dark' }}"></i>
                        <span>Berita</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a wire:navigate href="{{ route('admin.kegiatan.index') }}"
                        class="nav-link text-dark rounded-1 d-flex align-items-center gap-1 {{ request()->routeIs('admin.kegiatan.*') ? 'bg-primary text-light' : 'bg-white text-dark' }}">
                        <i
                            class="bi bi-clipboard-check {{ request()->routeIs('admin.kegiatan.*') ? 'text-light' : 'text-dark' }}"></i>
                        <span>Kegiatan</span>
                    </a>
                </li>
                <li class="nav-item" x-data="{ open: collapsed.orders }">
                    <button
                        class="btn btn-toggle w-100 d-flex align-items-center justify-content-between nav-link text-dark"
                        @click="open = !open; saveState('orders', open)">
                        <span class="d-flex align-items-center gap-1">
                            <i class="bi bi-folder"></i>
                            <span>Dokumen</span>
                        </span>
                        <i class="bi bi-caret-right transition-transform duration-300"
                            :style="{ transform: open ? 'rotate(90deg)' : 'rotate(0deg)' }">
                        </i>
                    </button>
                    <div class="collapse" :class="{ 'show': open }">
                        <ul class="btn-toggle-nav list-unstyled ps-4 pb-2">
                            <li class="nav-item"><a class="nav-link text-dark" href="#"> <i
                                        class="bi bi-dot"></i>RTRW</a>
                            </li>
                            <li class="nav-item"><a class="nav-link text-dark" href="#"> <i
                                        class="bi bi-dot"></i>RKPD</a>
                            </li>
                            <li class="nav-item"><a class="nav-link text-dark" href="#"> <i
                                        class="bi bi-dot"></i>RPJMD</a>
                            </li>
                            <li class="nav-item"><a class="nav-link text-dark" href="#"> <i
                                        class="bi bi-dot"></i>RPJPD</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</aside> --}}
