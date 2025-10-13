<div class="container py-4">
    <h3 class="mb-4 fw-bold text-primary">
      <i class="bi bi-speedometer2 me-2"></i>Dashboard Sistem
    </h3>

    <!-- Row pertama: 4 card -->
    <div class="row g-4">

      <!-- Card 1: Total Pegawai -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card text-center">
          <div class="card-body">
            <div class="d-flex justify-content-center align-items-center mb-1">
              <i class="bi bi-people-fill text-primary card-icon" style="font-size: 50px"></i>
            </div>
            <h5 class="card-title fw-semibold">Total Pegawai</h5>
            <h2 class="fw-bold text-primary count-up" data-target="128">{{ $totalPegawai }}</h2>
            <p class="text-muted">Jumlah pegawai aktif</p>
          </div>
        </div>
      </div>

      <!-- Card 2: Total Dokumen -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card text-center">
          <div class="card-body">
            <div class="d-flex justify-content-center align-items-center mb-1">
              <i class="bi bi-file-earmark-text-fill text-success card-icon" style="font-size: 50px"></i>
            </div>
            <h5 class="card-title fw-semibold">Total Dokumen</h5>
            <h2 class="fw-bold text-success count-up" data-target="452">{{ $totalDokumen }}</h2>
            <p class="text-muted">Dokumen yang terdaftar</p>
          </div>
        </div>
      </div>

      <!-- Card 3: Total Berita -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card text-center">
          <div class="card-body">
            <div class="d-flex justify-content-center align-items-center mb-1">
              <i class="bi bi-newspaper text-warning card-icon" style="font-size: 50px"></i>
            </div>
            <h5 class="card-title fw-semibold">Total Berita</h5>
            <h2 class="fw-bold text-warning count-up" data-target="36">{{ $totalBerita }}</h2>
            <p class="text-muted">Artikel berita dipublikasikan</p>
          </div>
        </div>
      </div>

      <!-- Card 4: Total Kegiatan -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card text-center">
          <div class="card-body">
            <div class="d-flex justify-content-center align-items-center mb-1">
              <i class="bi bi-calendar-event-fill text-danger card-icon" style="font-size: 50px"></i>
            </div>
            <h5 class="card-title fw-semibold">Total Kegiatan</h5>
            <h2 class="fw-bold text-danger count-up" data-target="14">{{ $totalKegiatan }}</h2>
            <p class="text-muted">Agenda kegiatan terbaru</p>
          </div>
        </div>
      </div>
    </div>
  </div>

