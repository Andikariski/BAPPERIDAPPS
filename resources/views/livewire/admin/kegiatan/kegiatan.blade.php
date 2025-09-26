<div>
    @php
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Data Kegiatan', 'url' => route('admin.kegiatan.index')],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />

    <!-- Header & Filters -->
    <div class="mt-4">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mb-1">
            <!-- Search & Filter -->
            <div class="row g-3">
                <div class="col-lg-6">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kegiatan..."
                        class="form-control">
                </div>
                <div class="col-lg-6">
                    <select wire:model.live="bidang_filter" class="form-select">
                        <option value="">Semua Bidang</option>
                        @foreach ($bidangList as $bidang)
                            <option value="{{ $bidang->id }}">{{ $bidang->nama_bidang }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <a href="{{ route('admin.kegiatan.create') }}"
                class="btn btn-primary rounded-1 d-inline-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Kegiatan</span>
            </a>
        </div>
    </div>

    <!-- Grid Data Kegiatan -->
    <div class="row">
        @forelse($dataKegiatan as $kegiatan)
            <div class="col-sm-6 col-lg-3">
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
                        <div class="mb-2">
                            <span class="badge p-1 bg-primary bg-opacity-10 text-primary">
                                {{ $kegiatan->bidang ? $kegiatan->bidang->nama_bidang : 'belum di tentukan' }}
                            </span>
                        </div>

                        <a wire:navigate href="{{ route('admin.kegiatan.detail', $kegiatan) }}"
                            class="card-title fw-semibold text-dark mb-2 text-decoration-none fs-5"
                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $kegiatan->nama_kegiatan }}
                        </a>

                        <div class="d-flex align-items-center justify-content-between mt-auto">
                            <span class="text-muted"
                                style="font-size: 0.75rem;">{{ $kegiatan->created_at->diffForHumans() }}</span>
                            <div class="d-flex gap-3 align-items-center">
                                <a href="{{ route('admin.kegiatan.edit', $kegiatan) }}"
                                    class="text-dark text-decoration-none" wire:navigate>
                                    <i class="bi bi-pencil-square "></i>
                                    <span>edit</span>
                                </a>
                                <!-- Updated Delete Button - menggunakan Livewire method -->
                                <button wire:click="$dispatch('confirm-delete-data-kegiatan',{{ $kegiatan }})"
                                    class="btn p-0 text-danger d-flex align-items-center" style="gap:4px;">
                                    <i class="bi bi-trash"></i>
                                    <span>hapus</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-file-earmark-text fs-1 text-dark"></i>
                    <h5 class="fw-medium text-dark mb-2">Tidak ada kegiatan</h5>
                    <p class="text-muted mb-3">Belum ada kegiatan yang tersedia. Silakan tambahkan kegiatan baru.</p>
                    <a href="{{ route('admin.kegiatan.create') }}"
                        class="btn btn-primary d-inline-flex align-items-center">
                        Tambah Kegiatan Pertama
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $dataKegiatan->links('vendor.livewire.bootstrap-pagination') }}
    </div>

</div>

<script>
    function openLightbox(kegiatanId) {
        const photos = document.querySelector(`[data-kegiatan-id="${kegiatanId}"]`);
        if (photos) {
            const firstPhoto = photos.querySelector('a[data-lightbox]');
            if (firstPhoto) {
                firstPhoto.click();
            }
        }
    }

    // Listen for Livewire events
    document.addEventListener('livewire:init', () => {
        Livewire.on('show-alert', (event) => {
            const {
                type,
                message
            } = event;

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: type,
                    title: type === 'success' ? 'Berhasil!' : 'Error!',
                    text: message,
                    timer: type === 'success' ? 2000 : 4000,
                    showConfirmButton: type === 'error'
                });
            } else {
                // alert(message);
                console.log(message)
            }
        });
    });
</script>
