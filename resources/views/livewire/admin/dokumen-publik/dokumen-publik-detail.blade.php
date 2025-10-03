<div>
    @php
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Data Dokumen Publik', 'url' => route('admin.dokumenpublik.index')],
            ['name' => 'Detail Dokumen Publik'],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />

    <div class="row my-4">
        <div class="col-md-4 mb-3">
            @if ($dokumen->thumbnail_path)
                <div class="text-start mb-4">
                    <img src="{{ Storage::url($dokumen->thumbnail_path) }}" alt="Thumbnail {{ $dokumen->nama_dokumen }}"
                        class="img-fluid rounded shadow-sm" style="max-height: 300px; object-fit: cover;">
                </div>
            @else
                <div class="text-center mb-4 bg-light rounded py-5">
                    <i class="bi bi-file-earmark-{{ strtolower($dokumen->file_type) }} display-1 text-muted"></i>
                </div>
            @endif

            <div class="mb-3">
                <small class="text-muted d-block m-0">nama file</small>
                <p class="fw-bold">{{ $dokumen->nama_dokumen }}</p>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block m-0">bidang</small>
                <p class="fw-bold">{{ $dokumen->bidang->nama_bidang ?? 'belum ditentukan' }}</p>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block m-0">tipe file</small>
                <p class="fw-bold">{{ strtoupper($dokumen->file_type) }}</p>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block m-0">ukuran file</small>
                <p class="fw-bold">{{ number_format($dokumen->file_size / 1024 / 1024, 2) }} MB</p>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block m-0">tangga ditambahkan</small>
                <p class="fw-bold">{{ $dokumen->created_at->format('d M Y H:i') }}</p>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block m-0">Deskripsi</small>
                <p class="fw-bold">{{ $dokumen->deskripsi_dokumen }}</p>
            </div>
            <div class="d-grid gap-2 mt-4">
                <button wire:click="download" class="btn btn-primary">
                    <i class="bi bi-download me-2"></i>
                    Download Dokumen
                </button>

                @if ($this->isViewable())
                    <button wire:click="toggleViewer" class="btn btn-secondary">
                        <i class="bi bi-{{ $showViewer ? 'eye-slash' : 'eye' }} me-2"></i>
                        {{ $showViewer ? 'Sembunyikan' : 'Tampilkan' }} Preview
                    </button>
                @endif
            </div>
        </div>
        <div class="col-md-8">
            @if ($this->isViewable() && $showViewer)
                <div class="card-body p-0">
                    @if (strtolower($dokumen->file_type) === 'pdf')
                        <div class="ratio ratio-4x3" style="min-height: 600px;">
                            <iframe src="{{ Storage::url($dokumen->file_path) }}#toolbar=1&navpanes=1&scrollbar=1"
                                type="application/pdf" class="border-0" style="width: 100%; height: 100%;">
                                <p class="p-4">
                                    Browser Anda tidak mendukung preview PDF.
                                    <a href="{{ Storage::url($dokumen->file_path) }}" target="_blank"
                                        class="btn btn-primary">
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
                    @elseif (in_array(strtolower($dokumen->file_type), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                        <div class="text-center p-4 bg-light">
                            <img src="{{ Storage::url($dokumen->file_path) }}" alt="{{ $dokumen->nama_dokumen }}"
                                class="img-fluid rounded shadow" style="max-height: 800px;">
                        </div>
                    @endif
                </div>
            @elseif (!$this->isViewable())
                <div class="text-center py-5">
                    <i class="bi bi-file-earmark-x display-1 text-muted mb-3"></i>
                    <h4 class="text-muted">Preview Tidak Tersedia</h4>
                    <p class="text-muted mb-4">
                        File dengan format <strong>{{ strtoupper($dokumen->file_type) }}</strong> tidak dapat
                        di-preview di browser.
                    </p>
                    <button wire:click="download" class="btn btn-success btn-lg">
                        <i class="bi bi-download me-2"></i>
                        Download untuk Melihat
                    </button>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-eye-slash display-1 text-muted mb-3"></i>
                        <h4 class="text-muted">Preview Disembunyikan</h4>
                        <p class="text-muted mb-4">
                            Klik tombol "Tampilkan Preview" untuk melihat dokumen.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
        <style>
            iframe {
                scroll-behavior: smooth;
            }
        </style>
    @endpush
