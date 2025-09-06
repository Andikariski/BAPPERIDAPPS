<aside class="sidebar py-2 px-4 rounded-3 bg-white d-flex flex-column shadow-sm" x-cloak
    :class="{ 'collapsed': !sidebarOpen }" style="transition: all 0.3s ease;">
    <div class="py-2">
        <h2 class="fs-5 text-center mb-4">BAPPERIDA PPS</h2>
    </div>
    <nav class="nav flex-column">
        <ul class="nav nav-pils flex-column">
            <li class="nav-item">
                <a wire:navigate href="{{ route('dashboard') }}"
                    class="nav-link rounded-2 d-flex align-items-center gap-1 {{ request()->routeIs('dashboard') ? 'bg-primary text-light' : 'bg-white text-dark' }}">
                    <i class="bi bi-speedometer2 {{ request()->routeIs('dashboard') ? 'text-light' : 'text-dark' }}"></i>
                    <span class="fs-6">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a wire:navigate href="{{ route('admin.posts.index') }}"
                    class="nav-link text-dark rounded-2 d-flex align-items-center gap-1 {{ request()->routeIs('admin.posts.*') ? 'bg-primary text-light' : 'bg-white text-dark' }}">
                    <i class="bi bi-book {{ request()->routeIs('admin.posts.*') ? 'text-light' : 'text-dark' }}"></i>
                    <span>Berita</span>
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
                                    class="bi bi-dot"></i>RTRW</a></li>
                        <li class="nav-item"><a class="nav-link text-dark" href="#"> <i
                                    class="bi bi-dot"></i>RKPD</a></li>
                        <li class="nav-item"><a class="nav-link text-dark" href="#"> <i
                                    class="bi bi-dot"></i>RPJMD</a></li>
                        <li class="nav-item"><a class="nav-link text-dark" href="#"> <i
                                    class="bi bi-dot"></i>RPJPD</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
</aside>
