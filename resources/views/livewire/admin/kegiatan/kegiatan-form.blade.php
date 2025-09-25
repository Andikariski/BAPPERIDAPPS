<div>
    @php
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Data Kegiatan', 'url' => route('admin.kegiatan.index')],
            ['name' => 'Tambah Data Kegiatan'],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />

    {{-- content page --}}
    <div>
        <h2 class="card-title h3 fw-bold mb-4">{{ $isEdit ? 'Edit' : 'Tambah' }} Kegiatan</h2>
        <form wire:submit="save" enctype="multipart/form-data">
            <!-- Bidang -->
            <div class="mb-3">
                <label class="form-label fw-medium">Bidang</label>
                <select wire:model="fkid_bidang" class="form-select">
                    <option value="">Pilih Bidang</option>
                    @foreach ($dataBidang as $bidang)
                        <option value="{{ $bidang->id }}">{{ $bidang->nama_bidang }}</option>
                    @endforeach
                </select>
                @error('fkid_bidang')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Judul Kegiatan -->
            <div class="mb-3">
                <label class="form-label fw-medium">Judul Kegiatan</label>
                <input type="text" wire:model="nama_kegiatan" class="form-control">
                @error('nama_kegiatan')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="mb-3">
                <label class="form-label fw-medium">Deskripsi Kegiatan</label>
                <textarea wire:model="deskripsi_kegiatan" rows="4" class="form-control"></textarea>
                @error('deskripsi_kegiatan')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Existing Photos -->
            @if ($isEdit && !empty($existingPhotos))
                <div class="mb-4">
                    <label class="form-label fw-medium">Foto Yang Ada</label>
                    <div id="existing-photos-sortable" class="row g-3">
                        @foreach ($existingPhotos as $photo)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="position-relative group-hover-container" data-photo-id="{{ $photo['id'] }}"
                                    style="cursor: move;">
                                    <img src="{{ Storage::url($photo->path_thumbnail) }}"
                                        alt="{{ $photo['nama_file'] }}" class="img-fluid rounded"
                                        style="height: 200px; width: 100%; object-fit: cover;">

                                    <!-- Photo Controls -->
                                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center rounded photo-overlay"
                                        style="background: rgba(0,0,0,0.5); opacity: 0; transition: opacity 0.3s ease;">
                                        <div class="d-flex gap-2">
                                            <!-- Set as Main Photo -->
                                            <button type="button" wire:click="setMainPhoto({{ $photo['id'] }})"
                                                class="bg-primary d-flex rounded align-items-center justify-content-center"
                                                style="height:70px; width:70px; border:none; outline:none"
                                                title="Jadikan foto utama">
                                                <i class="bi bi-star fs-4 text-white"></i>
                                            </button>

                                            <!-- View Full Size -->
                                            <a href="{{ $photo['url'] }}" data-lightbox="kegiatan-gallery"
                                                data-title="{{ $photo['caption'] }}"
                                                class="bg-secondary d-flex rounded align-items-center justify-content-center"
                                                style="height:70px; width:70px;" title="Lihat full size">
                                                <i class="bi bi-eye fs-4 text-white"></i>
                                            </a>

                                            <!-- Delete Photo -->
                                            <button type="button"
                                                wire:click="removeExistingPhoto({{ $photo['id'] }})"
                                                class="bg-danger d-flex rounded align-items-center justify-content-center"
                                                style="height:70px; width:70px; outline:none; border:none;"
                                                title="Hapus foto">
                                                <i class="bi bi-trash text-white fs-4"></i>

                                            </button>
                                        </div>
                                    </div>

                                    <!-- Main Photo Badge -->
                                    @if ($photo['is_main'])
                                        <div class="position-absolute top-0 start-0 m-2">
                                            <span class="badge bg-warning text-dark fw-bold"
                                                style="font-size: 0.75rem;">UTAMA</span>
                                        </div>
                                    @endif

                                    <!-- Drag Handle -->
                                    <div class="position-absolute top-0 end-0 m-2 text-white" style="cursor: move;">
                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Upload New Photos -->
            <div class="mb-4">
                <label class="form-label fw-medium">Upload Foto Baru</label>

                <!-- Dropzone -->
                <div id="dropzone" class="rounded border-primary p-5 text-center dropzone-area"
                    style="border-style:dashed;">
                    <input type="file" wire:model="photos" multiple accept="image/*" class="d-none" id="file-input">
                    <div>
                        <svg class="mx-auto mb-3 text-muted" width="48" height="48" stroke="currentColor"
                            fill="none" viewBox="0 0 48 48">
                            <path
                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="small text-muted">
                            <label for="file-input" class="text-primary text-decoration-none"
                                style="cursor: pointer;">Klik untuk upload</label>
                            atau drag & drop foto di sini
                        </div>
                        <p class="text-muted mb-0" style="font-size: 0.75rem;">PNG, JPG, GIF sampai 10MB
                            per file</p>
                    </div>
                </div>
                @error('photos.*')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Photo Previews -->
            @if (!empty($photos))
                <div class="mb-4">
                    <label class="form-label fw-medium">Preview Foto Baru</label>
                    <div class="row g-3">
                        @foreach ($photos as $index => $photo)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="position-relative">
                                    <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="img-fluid rounded"
                                        style="height: 128px; width: 100%; object-fit: cover;">
                                    <button type="button" wire:click="removePhoto({{ $index }})"
                                        class="position-absolute top-0 end-0 m-2 btn btn-danger btn-sm d-flex align-items-center justify-content-center"
                                        style="height: 40px; width:40px; outline: none; border:none;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <input type="text" wire:model="captions.{{ $index }}"
                                        placeholder="Caption foto..." class="form-control form-control-sm mt-2">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Submit Button -->
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <span wire:loading.remove>{{ $isEdit ? 'Update' : 'Simpan' }}</span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="container mx-auto px-4 mt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container mx-auto px-4 mt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Sortable for existing photos
            if (document.getElementById('existing-photos-sortable')) {
                new Sortable(document.getElementById('existing-photos-sortable'), {
                    animation: 150,
                    ghostClass: 'opacity-50',
                    onEnd: function(evt) {
                        let orderedIds = Array.from(evt.to.children).map(el => el.dataset.photoId);
                        @this.call('updatePhotoOrder', orderedIds);
                    }
                });
            }

            // Dropzone functionality
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('file-input');

            if (dropzone && fileInput) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropzone.addEventListener(eventName, preventDefaults, false);
                    document.body.addEventListener(eventName, preventDefaults, false);
                });

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropzone.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropzone.addEventListener(eventName, unhighlight, false);
                });

                dropzone.addEventListener('drop', handleDrop, false);

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                function highlight(e) {
                    dropzone.classList.add('border-blue-500', 'bg-blue-50');
                }

                function unhighlight(e) {
                    dropzone.classList.remove('border-blue-500', 'bg-blue-50');
                }

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    fileInput.files = files;
                    fileInput.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }
            }
        });

        // Listen for Livewire events
        document.addEventListener('livewire:init', () => {
            Livewire.on('photo-updated', (message) => {
                // Show success notification
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    alert(message);
                }
            });
        });
    </script>
@endpush
