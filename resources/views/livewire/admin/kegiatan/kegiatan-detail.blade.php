<div>
    @php
        $breadcrumbs = [
            ['name' => 'Beranda', 'url' => route('dashboard')],
            ['name' => 'Data Kegiatan', 'url' => route('admin.kegiatan.index')],
            ['name' => 'Detail Kegiatan'],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <div class="mt-4">
        <div class="row g-5">
            <div class="col-sm-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Informasi Kegiatan</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nama Kegiatan:</strong><br>{{ $kegiatan->nama_kegiatan }}</p>
                        <p>{{ $kegiatan->deskripsi_kegiatan }}</p>

                        <p><strong>Bidang:</strong><br>
                            @if ($kegiatan->bidang)
                                <span class="badge bg-info">{{ $kegiatan->bidang->nama_bidang }}</span>
                            @else
                                <span class="text-muted">Belum ditentukan</span>
                            @endif
                        </p>

                        <p><strong>Jumlah Foto:</strong><br>
                            <span class="badge bg-success">{{ $kegiatan->fotoKegiatan->count() }} foto</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-8">
                <!-- Semua Foto Kegiatan -->
                @if ($kegiatan->fotoKegiatan->count() > 0)
                    <div class="gallery mb-4">
                        <h3>Galeri Foto</h3>
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
