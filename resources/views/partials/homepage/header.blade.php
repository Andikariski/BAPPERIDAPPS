{{-- <header id="header" class="header d-flex align-items-center sticky-top"> --}}
    <header id="header" class="header transparent-header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
        <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <img src="assets/img/weblogo1.PNG" alt="" style="height: 100%; width: 100%;"/>
            {{-- <h4 class="sitename" style="font-weight: bold; color:white">BAPPERIDA PPS</h4> --}}
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li>
                    <a href="{{ route('home') }}" class="active" style="color:white"><strong>Beranda</strong></a>
                </li>

                {{-- <li><a href="{{ route('blog.index') }}">Blog</a></li> --}}
                <li class="dropdown">
                    <a href="#"><span style="color:white; font-weight: bold;" class="tentang">Tentang</span>
                        <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        {{-- <li><a href="#">Pengantar Kepala Badan</a></li> --}}
                        <li><a href="{{ route('pegawai') }}" sty>Profile Pegawai</a></li>
                        <li><a href="{{ route('strukrOrganisasi') }}">Struktur Organisasi</a></li>
                        <li><a href="{{ route('tupoksi') }}">Tugas Pokok dan Fungsi</a></li>
                    </ul>
                </li>

                <li><a href="{{ route('blog.index') }}" style="color:white"><strong>Berita</strong></a></li>
                <li><a href="{{ route('kegiatan.index') }}" style="color:white"><strong>Kegiatan</strong></a></li>
                <li><a href="{{ route('kegiatan.index') }}" style="color:white"><strong>Infografis</strong></a></li>

                <li><a href="{{ route('dokumenpublik') }}" style="color:white"><strong>Dokumen Publik</strong></a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        {{-- <a class="btn-getstarted" href="{{ route('login') }}">Masuk</a> --}}
        {{-- <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"> Log in</a> --}}
    </div>
</header>

<script>
window.addEventListener('scroll', function() {
    const header = document.getElementById('header');
    if(window.scrollY > 50){
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});
</script>
