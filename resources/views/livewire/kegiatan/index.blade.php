<div class="container py-2">
    <div class="container section-title mt-5" data-aos="fade-up" wire:ignore>
        <h2>Kegiatan</h2>
        <p>Foto Foto Kegiatan dan Aktivitas BAPPERIDA PPS</p>
    </div>
    <!-- Search & filtering -->
    <div class="d-flex justify-content-between gap-2">
        <input type="text" class="form-control mb-3" placeholder="cari Kegiatan..."
            wire:model.live.debounce.500ms="search">
        <div class="mb-3">
            <select wire:model.live="selectedBidang" class="form-select">
                <option class="px-4" value="">Semua Bidang</option>
                @foreach ($dataBidang as $bidang)
                    <option value="{{ $bidang->id }}">{{ $bidang->nama_bidang }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        @forelse($dataKegiatan as $kegiatan)
            <div class="col-lg-4">
                <div class="card overflow-hidden rounded-2 h-100">
                    <!-- Photo section -->
                    <div class="position-relative bg-light" style="height: 192px;">
                        @php
                            $fotoUtama = $kegiatan->getFotoUtama();
                        @endphp
                        @if ($fotoUtama)
                            <img src="{{ Storage::url($fotoUtama->path_thumbnail) }}"
                                alt="{{ $kegiatan->nama_kegiatan }}" class="w-100 h-100" style="object-fit: cover;">

                            @if ($kegiatan->total_foto > 1)
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span
                                        class="p-1 rounded gap-1 bg-dark bg-opacity-75 text-white d-flex align-items-center">
                                        <i class="bi bi-images"></i>
                                        {{ $kegiatan->total_foto }}
                                    </span>
                                </div>
                            @endif
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                <i class="bi bi-card-image fs-1"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <div class="mb-1">
                            <small class="">
                                {{ $kegiatan->bidang->nama_bidang ?? '-belum ditentukan-' }}
                            </small>
                        </div>

                        <a href="{{ route('kegiatan.show', $kegiatan->id) }}"
                            class="card-title mb-3 fw-semibold text-primary text-decoration-none fs-5"
                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ Str::limit(strip_tags($kegiatan->nama_kegiatan),50) }}
                        </a>

                        <div class="d-flex align-items-center justify-content-between mt-auto">
                            <span class="text-muted"
                                style="font-size: 0.75rem;">{{ $kegiatan->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-warning d-flex align-items-center justify-content-center">
                <p class="my-5">tidak ada berita ditemukan</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $dataKegiatan->links('vendor.livewire.bootstrap-pagination') }}
    </div>
</div>
