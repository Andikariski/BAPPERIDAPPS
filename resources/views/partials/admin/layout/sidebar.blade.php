<div class="sidebar bg-white border border-end-light" :class="{ 'collapsed': sidebarCollapsed, 'show': showSidebar }">
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
                <a wire:navigate href="{{ route('admin.berita.index') }}"
                    class="sidebar-nav-link text-dark rounded-1 d-flex align-items-center gap-1 {{ request()->routeIs('admin.berita.*') ? 'bg-primary text-light' : 'bg-white text-dark' }}"
                    :class="{ 'justify-content-center': sidebarCollapsed }">
                    <i
                        class="bi bi-journal-text {{ request()->routeIs('admin.berita.*') ? 'text-light' : 'text-dark' }}"></i>
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
            <li class="nav-item">
                <a wire:navigate href="{{ route('admin.bidang.index') }}"
                    class="sidebar-nav-link text-dark rounded-1 d-flex align-items-center gap-1 {{ request()->routeIs('admin.bidang.*') ? 'bg-primary text-light' : 'bg-white text-dark' }}"
                    :class="{ 'justify-content-center': sidebarCollapsed }">
                    <i
                        class="bi bi-diagram-2 {{ request()->routeIs('admin.bidang.*') ? 'text-light' : 'text-dark' }}"></i>
                    <span x-show="!sidebarCollapsed">Bidang</span>
                </a>
            </li>
            <li class="nav-item">
                <a wire:navigate href="{{ route('admin.pegawai.index') }}"
                    class="sidebar-nav-link text-dark rounded-1 d-flex align-items-center gap-1 {{ request()->routeIs('admin.pegawai.index') ? 'bg-primary text-light' : 'bg-white text-dark' }}"
                    :class="{ 'justify-content-center': sidebarCollapsed }">
                    <i
                        class="bi bi-people {{ request()->routeIs('admin.pegawai.index') ? 'text-light' : 'text-dark' }}"></i>
                    <span x-show="!sidebarCollapsed">Pegawai</span>
                </a>
            </li>
            <li class="nav-item">
                <a wire:navigate href="{{ route('admin.jabatan.index') }}"
                    class="sidebar-nav-link text-dark rounded-1 d-flex align-items-center gap-1 {{ request()->routeIs('admin.jabatan.*') ? 'bg-primary text-light' : 'bg-white text-dark' }}"
                    :class="{ 'justify-content-center': sidebarCollapsed }">
                    <i
                        class="bi bi-person-vcard {{ request()->routeIs('admin.jabatan.*') ? 'text-light' : 'text-dark' }}"></i>
                    <span x-show="!sidebarCollapsed">Jabatan</span>
                </a>
            </li>
            <li class="nav-item">
                <a wire:navigate href="{{ route('admin.dokumenpublik.index') }}"
                    class="sidebar-nav-link text-dark rounded-1 d-flex align-items-center gap-1 {{ request()->routeIs('admin.dokumenpublik.*') ? 'bg-primary text-light' : 'bg-white text-dark' }}"
                    :class="{ 'justify-content-center': sidebarCollapsed }">
                    <i
                        class="bi bi-folder-symlink {{ request()->routeIs('admin.dokumenpublik.*') ? 'text-light' : 'text-dark' }}"></i>
                    <span x-show="!sidebarCollapsed">Dokumen</span>
                </a>
            </li>
            <li class="nav-item invisible" x-data="{ open: false }">
                <!-- Parent link (dropdown trigger) -->
                <a href="#" @click.prevent="open = !open"
                    class="sidebar-nav-link text-dark rounded-1 d-flex align-items-center justify-content-between gap-1 {{ request()->routeIs('admin.rap.*') ? 'bg-primary text-light' : 'bg-white text-dark' }}"
                    :class="{ 'justify-content-center': sidebarCollapsed }">
                    <div class="d-flex align-items-center gap-1">
                        <i
                            class="bi bi-cash-stack {{ request()->routeIs('admin.rap.*') ? 'text-light' : 'text-dark' }}"></i>
                        <span x-show="!sidebarCollapsed">RAP</span>
                    </div>
                    <i class="bi" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'"
                        x-show="!sidebarCollapsed"></i>
                </a>

                <!-- Dropdown menu -->
                <ul class="list-unstyled ps-4 mt-1" x-show="open && !sidebarCollapsed" x-transition
                    style="display: none;">
                    <li>
                        <a wire:navigate href="{{ route('admin.rap.index', ['type' => 'awal']) }}"
                            class="d-block py-1 text-decoration-none {{ request()->get('type') === 'awal' ? 'fw-bold text-primary' : 'text-dark' }}">
                            RAP Awal
                        </a>
                    </li>
                    <li>
                        <a wire:navigate href="{{ route('admin.rap.index', ['type' => 'perubahan']) }}"
                            class="d-block py-1 text-decoration-none {{ request()->get('type') === 'perubahan' ? 'fw-bold text-primary' : 'text-dark' }}">
                            RAP Perubahan
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</div>
