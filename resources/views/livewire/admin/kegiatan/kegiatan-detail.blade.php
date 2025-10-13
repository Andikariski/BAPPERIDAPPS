<div>
    @php
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Data Kegiatan', 'url' => route('admin.kegiatan.index')],
            ['name' => 'Detail Kegiatan'],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />
    <div class="mt-3">
        <div class="row g-5">
            <div class="col-sm-12 col-lg-4">
                <div class="text-dark">
                    <p class="fs-5 fw-semibold py-1 px-2 bg-light rounded">Informasi Kegiatan</p>
                    {{-- <hr class="text-dark" class="padding-top:-4px;"> --}}
                    <div class="">
                        <p class="fw-semibold">{{ $kegiatan->nama_kegiatan }}</p>
                        <p>{{ $kegiatan->deskripsi_kegiatan }}</p>
                        <p style="text-decoration: underline">Bidang:
                            @if ($kegiatan->bidang)
                                {{ $kegiatan->bidang->nama_bidang }}
                            @else
                                <span class="text-muted">Belum ditentukan</span>
                            @endif
                        </p>
                        {{-- <p><strong>Jumlah Foto:</strong><br>
                            <span class="badge bg-success">{{ $kegiatan->fotoKegiatan->count() }} foto</span>
                        </p> --}}
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-8">
                <!-- Semua Foto Kegiatan -->
                @if ($kegiatan->fotoKegiatan->count() > 0)
                    <div class="gallery mb-4 text-dark">
                        <div class="d-flex align-items-center gap-3 py-1 px-2 bg-light rounded">
                            <p class="fs-5 fw-semibold">Galeri Foto</p>
                            <div class="d-flex align-items-center gap-1">
                                {{-- <i class="bg-danger bi bi-images"></i> --}}
                                <p class="">{{ $kegiatan->fotoKegiatan->count() }} foto</p>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($kegiatan->fotoKegiatan as $foto)
                                <div class="col-12 col-md-4 col-lg-3 mb-3">
                                    <div class="rounded overflow-hidden position-relative">
                                        <img src="{{ Storage::url($foto->path_file) }}" alt="Foto Kegiatan"
                                            class="card-img-top">
                                        @if ($foto->is_main)
                                            <div class="badge bg-primary position-absolute top-0 start-0 m-2">
                                                Foto Utama
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        Belum ada foto untuk kegiatan ini.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
