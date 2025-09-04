<header id="header" class="header d-flex align-items-center sticky-top">
      <div
        class="container-fluid container-xl position-relative d-flex align-items-center">
        <a href="index.html" class="logo d-flex align-items-center me-auto">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <img src="assets/img/pps.png" alt="" />
          <h4 class="sitename">BAPPERIDA PPS</h4>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li>
              <a href="#hero" class="active">Beranda<br /></a>
            </li>
            <li class="dropdown">
              <a href="#"
                ><span>Tentang</span>
                <i class="bi bi-chevron-down toggle-dropdown"></i
              ></a>
              <ul>
                <li><a href="#">Pengantar Kepala Badan</a></li>
                <li><a href="#">Profile Pegawai</a></li>
                <li><a href="#">Struktur Organisasi</a></li>
                <li><a href="#">Tugas Pokok dan Fungsi</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#"
                ><span>Berita</span>
                <i class="bi bi-chevron-down toggle-dropdown"></i
              ></a>
              <ul>
                <li><a href="#">Berita dan Informasi</a></li>
                <li><a href="#">Galeri Foto</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#"
                ><span>Dokumen Publik</span>
                <i class="bi bi-chevron-down toggle-dropdown"></i
              ></a>
              <ul>
                <li><a href="#">RKPD</a></li>
                <li><a href="#">RTRW</a></li>
                <li><a href="#">RPJMD</a></li>
                <li><a href="#">RPJPD</a></li>
                <li><a href="#">LKPJ</a></li>
                <li><a href="#">Dokumen Lain</a></li>
              </ul>
            </li>
            <li><a href="#about">Informasi Lainya</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="btn-getstarted" href="{{ route('login') }}">Masuk</a>
        {{-- <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"> Log in</a> --}}
      </div>
    </header>
