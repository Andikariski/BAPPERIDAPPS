<div class="container py-2">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}">Kegiatan</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $kegiatan->nama_kegiatan }}</li>
        </ol>
    </nav>
    <div class="my-2">
        <div class="mb-5">
            <p class="fw-semibold fs-3">{{ $kegiatan->nama_kegiatan }}</p>
            <p>{{ $kegiatan->deskripsi_kegiatan }}</p>
            <p class="fw-semibold">Bidang: {{ $kegiatan->bidang->nama_bidang ?? 'belum ditentukan' }}
            </p>
        </div>
        <div class="">
            @if ($kegiatan->fotoKegiatan->count() > 0)
                <div class="row">
                    @foreach ($kegiatan->fotoKegiatan->sortBy('urutan') as $foto)
                        <div class="col-12 col-md-4 col-lg-3 mb-3">
                            <div class="card border-0 shadow-sm photo-card" style="cursor: pointer;"
                                wire:click="openModal({{ $foto->id }})">

                                <!-- Photo Container -->
                                <div class="position-relative overflow-hidden">
                                    <img src="{{ Storage::url($foto->path_thumbnail ?? $foto->path_file) }}"
                                        alt="{{ $foto->caption ?? 'Foto Kegiatan' }}" class="card-img-top"
                                        style="height: 200px; object-fit: cover; transition: transform 0.3s ease;">

                                    <!-- Badges -->
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge bg-dark bg-opacity-75">
                                            #{{ $foto->urutan }}
                                        </span>
                                    </div>

                                    <!-- Hover Overlay -->
                                    <div
                                        class="photo-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-zoom-in text-white" style="font-size: 2.5rem;"></i>
                                    </div>
                                </div>

                                <!-- Caption (if exists) -->
                                @if ($foto->caption)
                                    <div class="card-body p-2">
                                        <p class="card-text text-muted small mb-0 text-truncate">
                                            {{ $foto->caption }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Photo Modal -->
                <div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content border-0">
                            <!-- Header -->
                            <div class="modal-header border-0 pb-0">
                                <button type="button" class="btn-close btn-close" wire:click="closeModal"
                                    data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- Body -->
                            <div class="modal-body p-0 position-relative">
                                @if ($selectedPhoto)
                                    <div class="text-center p-4">
                                        <img src="{{ Storage::url($selectedPhoto->path_file) }}"
                                            alt="{{ $selectedPhoto->caption ?? 'Foto Kegiatan' }}"
                                            class="img-fluid rounded"
                                            style="max-height: 70vh; width: auto; object-fit: contain;">
                                    </div>

                                    <!-- Navigation Buttons -->
                                    @if ($kegiatan->fotoKegiatan->count() > 1)
                                        <button
                                            class="btn btn-dark position-absolute top-50 start-0 translate-middle-y ms-3 rounded-circle shadow"
                                            wire:click="previousPhoto"
                                            style="width: 50px; height: 50px; opacity: 0.9; z-index: 10;"
                                            title="Foto Sebelumnya (←)">
                                            <i class="bi bi-chevron-left fs-5"></i>
                                        </button>

                                        <button
                                            class="btn btn-dark position-absolute top-50 end-0 translate-middle-y me-3 rounded-circle shadow"
                                            wire:click="nextPhoto"
                                            style="width: 50px; height: 50px; opacity: 0.9; z-index: 10;"
                                            title="Foto Selanjutnya (→)">
                                            <i class="bi bi-chevron-right fs-5"></i>
                                        </button>
                                    @endif
                                @endif
                            </div>

                            <!-- Footer -->
                            @if ($selectedPhoto)
                                <div class="modal-footer border-0 flex-column align-items-start">
                                    <!-- Photo Counter -->
                                    <div class="d-flex justify-content-between w-100 mb-2">
                                        <span class="">
                                            @php
                                                $position = $this->getCurrentPhotoPosition();
                                            @endphp
                                            Foto {{ $position['current'] }} dari {{ $position['total'] }}
                                        </span>
                                    </div>

                                    <!-- Caption -->
                                    @if ($selectedPhoto->caption)
                                        <div class="w-100">
                                            <p class=" mb-0">
                                                <i class="bi bi-chat-left-text me-2"></i>
                                                {{ $selectedPhoto->caption }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endif
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

@push('styles')
    <style>
        .photo-card {
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .photo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .photo-card:hover img {
            transform: scale(1.1);
        }

        .photo-overlay {
            background: rgba(0, 0, 0, 0.6);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .photo-card:hover .photo-overlay {
            opacity: 1;
        }

        /* Modal Backdrop Blur */
        .modal-backdrop.show {
            backdrop-filter: blur(5px);
        }

        /* Button Hover Effects */
        .modal-body button:hover {
            opacity: 1 !important;
            transform: translateY(-50%) scale(1.1);
        }
    </style>
@endpush

@push('scripts')
    <script>
        let photoModal;

        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('photoModal');
            if (modalElement) {
                photoModal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: false
                });
            }
        });

        // Listen untuk event open modal
        window.addEventListener('open-photo-modal', () => {
            if (photoModal) {
                photoModal.show();
            }
        });

        // Listen untuk event close modal
        window.addEventListener('close-photo-modal', () => {
            if (photoModal) {
                photoModal.hide();
            }
        });

        // Keyboard Navigation
        document.addEventListener('keydown', function(e) {
            if (photoModal && photoModal._isShown) {
                if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    @this.call('nextPhoto');
                } else if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    @this.call('previousPhoto');
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    @this.call('closeModal');
                }
            }
        });

        // Prevent body scroll when modal is open
        document.addEventListener('shown.bs.modal', function() {
            document.body.style.overflow = 'hidden';
        });

        document.addEventListener('hidden.bs.modal', function() {
            document.body.style.overflow = 'auto';
        });
    </script>
@endpush
