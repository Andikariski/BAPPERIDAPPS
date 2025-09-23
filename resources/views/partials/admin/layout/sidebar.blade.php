<div class="sidebar bg-white " :class="{ 'collapsed': sidebarCollapsed, 'show': showSidebar }">
    <div class=" d-flex flex-column align-items-center w-100 rounded-lg h-100 px-2 py-2">
        <div class="brand-wrapper px-2" style="">
            <div class="d-flex align-items-center">
                <a href="{{ route('dashboard') }}">
                    <div class="oveflow-hidden d-flex align-items-center justify-content-center"
                        style="height: 60px; width: auto" :class="{ 'me-2': !sidebarCollapsed }">
                        <img style="object-fit: cover; height: 100%; width: auto" src="{{ asset('assets/img/pps.png') }}"
                            alt="">
                    </div>
                </a>
                <p class="fw-semibold fs-5 text-dark" x-show="!sidebarCollapsed">BAPPERIDA
                    PPS</p>
            </div>
            <button class="btn btn-outline-dark" x-show="showSidebar" type="button" @click="showSidebar = false">
                <i class="bi bi-x fw-2"></i>
            </button>
        </div>

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
