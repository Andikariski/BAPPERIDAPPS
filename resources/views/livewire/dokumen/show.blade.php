<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dokumenpublik') }}">Daftar Dokumen</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $dokumen->nama_dokumen }}</li>
        </ol>
    </nav>
    <div class="my-4">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <p class="m-0">nama dokumen</p>
                    <p class="fw-bold">{{ $dokumen->nama_dokumen }}</p>
                </div>
                <div class="mb-3">
                    <p class="m-0">Bidang</p>
                    <p class="fw-bold">{{ $dokumen->bidang->nama_bidang ?? 'belum ditentukan' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <p class="m-0">Bidang</p>
                    <p class="fw-bold">{{ $dokumen->bidang->nama_bidang ?? 'belum ditentukan' }}</p>
                </div>
                <div class="mb-3">
                    <p class="d-block m-0">tangga ditambahkan</p>
                    <p class="fw-bold">{{ $dokumen->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
            <div class="mb-3">
                <p class=" d-block m-0">Deskripsi</p>
                <p class="fw-bold">{{ $dokumen->deskripsi_dokumen }}</p>
            </div>
        </div>
        <div class="my-5 card">
            <div class="ratio ratio-4x3" style="min-height: 600px;">
                <iframe src="{{ Storage::url($dokumen->file_path) }}#toolbar=1&navpanes=1&scrollbar=1"
                    type="application/pdf" class="border-0" style="width: 100%; height: 100%;">
                    <p class="p-4">
                        Browser Anda tidak mendukung preview PDF.
                        <a href="{{ Storage::url($dokumen->file_path) }}" target="_blank" class="btn btn-primary">
                            Buka di Tab Baru
                        </a>
                    </p>
                </iframe>
            </div>

            <div class="card-footer bg-light">
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Tidak bisa melihat preview?
                    <a href="{{ Storage::url($dokumen->file_path) }}" target="_blank" class="fw-bold">
                        Buka di tab baru
                    </a>
                </small>
            </div>
        </div>
    </div>
</div>
