<div>
    @php
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Data berita', 'url' => route('admin.berita.index')],
            ['name' => 'Tambah Data berita', 'url' => route('admin.berita.create')],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />
    <div class="my-4">
        <form wire:submit.prevent="save">
            <div class="card-body">
                <form wire:submit.prevent="save">
                    <div class="row g-4">
                        <div class="col-12 col-lg-8">
                            <!-- Judul Berita -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="judulBerita">Judul Berita</label>
                                <input type="text" wire:model.live="judulBerita"
                                    class="form-control @error('judulBerita') is-invalid @enderror" id="judulBerita">
                                @error('judulBerita')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Slug -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="slug">Slug</label>
                                <input type="text" wire:model="slug"
                                    class="form-control @error('slug') is-invalid @enderror" id="slug">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Konten Artikel -->
                            <div class="mb-3" wire:ignore>
                                <label class="form-label fw-semibold">Konten Berita</label>
                                <div class="border rounded" style="height: 550px; overflow: auto;">
                                    <div id="editor"></div>
                                </div>
                                <input type="hidden" wire:model="kontenBerita" id="kontenBerita">
                                @error('kontenBerita')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary d-flex align-items-center gap-2"
                                    wire:loading.attr="disabled">
                                    <span wire:loading>
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </span>
                                    <span wire:loading.remove>
                                        {{ $isEdit ? 'Update Berita' : 'Simpan Berita' }}
                                    </span>
                                    <span wire:loading>
                                        {{ $isEdit ? 'Mengupdate...' : 'Menambahkan...' }}
                                    </span>
                                </button>

                                <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">
                                    Batal
                                </a>
                            </div>
                        </div>

                        <div class="col-12 col-lg-4">
                            <!-- Thumbnail -->
                            <div class="mb-3" x-data="{ fileName: 'Pilih foto untuk thumbnail artikel' }">
                                <label class="form-label fw-semibold">Foto Thumbnail Berita</label>

                                <div class="input-group">
                                    <input type="text" class="form-control" x-model="fileName" readonly>
                                    <label class="input-group-text btn btn-outline-secondary">
                                        Pilih
                                        <input type="file" wire:model="fotoThumbnail" class="d-none" accept="image/*"
                                            @change="fileName = $event.target.files.length ? $event.target.files[0].name : 'Pilih foto untuk thumbnail berita'">
                                    </label>
                                </div>

                                @error('fotoThumbnail')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror

                                <!-- Preview for new upload -->
                                @if ($fotoThumbnail)
                                    <div class="mt-2">
                                        <p class="small text-muted">Preview:</p>
                                        <img src="{{ $fotoThumbnail->temporaryUrl() }}" alt="Preview"
                                            class="img-fluid rounded shadow-sm"
                                            style="height: 150px; object-fit: cover; width: 100%;">
                                    </div>
                                @elseif($isEdit && $existingThumbnail)
                                    <!-- Preview for existing thumbnail -->
                                    <div class="mt-2">
                                        <p class="small text-muted">Thumbnail saat ini:</p>
                                        <img src="{{ asset('storage/foto_thumbnail_berita/' . $existingThumbnail) }}"
                                            alt="Current Thumbnail" class="img-fluid rounded shadow-sm"
                                            style="height: 150px; object-fit: cover; width: 100%;">
                                    </div>
                                @endif
                            </div>

                            <!-- Bidang Pelaksana -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Bidang Pelaksana</label>
                                <select wire:model="bidangPelaksanaId"
                                    class="form-select @error('bidangPelaksanaId') is-invalid @enderror">
                                    <option value="">-- Pilih Bidang --</option>
                                    @foreach ($dataBidang as $bidang)
                                        <option value="{{ $bidang->id }}">{{ $bidang->nama_bidang }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bidangPelaksanaId')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tags -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tags Berita</label>
                                <div class="d-flex flex-column gap-1">
                                    @foreach ($tags as $tag)
                                        <div class="form-check">
                                            <input class="form-check-input @error('tagIds') is-invalid @enderror"
                                                type="checkbox" value="{{ $tag->id }}" wire:model="tagIds"
                                                id="tag_{{ $tag->id }}">
                                            <label class="form-check-label" for="tag_{{ $tag->id }}">
                                                {{ $tag->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('tagIds')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status Publikasi -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status Publikasi</label>
                                <select wire:model="statusPublikasi"
                                    class="form-select @error('statusPublikasi') is-invalid @enderror">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                                @error('statusPublikasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </form>
    </div>
</div>
@script
    <script>
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{
                        'header': 1
                    }, {
                        'header': 2
                    }],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    ['image', 'link'],
                    ['align', {
                        'align': 'center'
                    }],
                    ['clean']
                ]
            }
        });

        const kontenBeritaInput = document.querySelector('#kontenBerita');

        // Update hidden input saat Quill berubah
        quill.on('text-change', function() {
            const html = quill.root.innerHTML;
            kontenBeritaInput.value = html;

            kontenBeritaInput.dispatchEvent(new Event('input'));
        });

        // Set konten awal dari Livewire ke Quill
        window.addEventListener('populate-quill', event => {
            if (quill) {
                quill.root.innerHTML = event.detail.contentPost || '';
            } else {
                setTimeout(() => {
                    if (quill) quill.root.innerHTML = event.detail.contentPost || '';
                }, 300);
            }
        });

        // Kalau mau set value awal dari Livewire ke Quill
        Livewire.hook('message.processed', (message, component) => {
            if (@this.kontenBerita && quill.root.innerHTML !== @this.kontenBerita) {
                quill.root.innerHTML = @this.kontenBerita;
            }
        });

        // handle image upload
        quill.getModule('toolbar').addHandler('image', function() {
            @this.set('kontenBerita', quill.root.innerHTML);

            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = function() {
                var file = input.files[0];
                if (file) {
                    var reader = new FileReader();

                    reader.onload = function(event) {
                        var base64Data = event.target.result;

                        @this.uploadImage(base64Data);
                    };
                    // Read the file as a data URL (base64)
                    reader.readAsDataURL(file);
                }
            };
        });
        let previousImages = [];

        quill.on('text-change', function(delta, oldDelta, source) {
            var currentImages = [];

            var container = quill.container.firstChild;

            container.querySelectorAll('img').forEach(function(img) {
                currentImages.push(img.src);
            });

            var removedImages = previousImages.filter(function(image) {
                return !currentImages.includes(image);
            });

            removedImages.forEach(function(image) {
                @this.deleteImage(image);
                console.log('Image removed:', image);
            });

            // Update the previous list of images
            previousImages = currentImages;
        });

        Livewire.on('blog-image-uploaded', function(imagePaths) {
            if (Array.isArray(imagePaths) && imagePaths.length > 0) {
                var imagePath = imagePaths[0]; // Extract the first image path from the array
                console.log('Received imagePath:', imagePath);

                if (imagePath && imagePath.trim() !== '') {
                    var range = quill.getSelection(true);
                    quill.insertText(range ? range.index : quill.getLength(), '\n', 'user');
                    quill.insertEmbed(range ? range.index + 1 : quill.getLength(), 'image', imagePath);
                } else {
                    console.warn('Received empty or invalid imagePath');
                }
            } else {
                console.warn('Received empty or invalid imagePaths array');
            }
        });
    </script>
@endscript
