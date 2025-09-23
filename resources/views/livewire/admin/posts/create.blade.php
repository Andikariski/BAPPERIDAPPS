<div>
    @php
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Artikel', 'url' => route('admin.posts.index')],
            ['name' => 'Buat Berita Baru'],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />

    <div class="mt-4">
        <form wire:submit.prevent="{{ isset($post) ? 'update' : 'save' }}">
            <div class="row g-4">
                <!-- Kolom Kiri -->
                <div class="col-12 col-lg-8">
                    <!-- Judul Artikel -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Berita</label>
                        <input type="text" wire:model="title" wire:model="post.title" class="form-control">
                        @error('title')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Konten Artikel -->
                    <div class="mb-3" wire:ignore>
                        <label class="form-label fw-semibold">Konten Berita</label>
                        <div class="border rounded" style="height: 550px; overflow: auto;">
                            <div id="editor"></div>
                        </div>
                        <input type="hidden" wire:model="content" id="content">
                        @error('content')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                        <span wire:loading>
                            <div class="spinner-border spinner-border-sm text-dark" role="status">
                                <span class="visually-hidden">loading...</span>
                            </div>
                        </span>
                        <span wire:loading.remove>
                            Simpan Berita
                        </span>
                        <span wire:loading>
                            Menambahkan...
                        </span>
                    </button>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-12 col-lg-4">
                    <!-- Thumbnail -->
                    <div class="mb-3" x-data="{ fileName: 'Pilih foto untuk thumbnail artikel' }">
                        <label class="form-label fw-semibold">Foto Thumbnail Berita</label>

                        <div class="input-group">
                            <input type="text" class="form-control" x-model="fileName" readonly>
                            <label class="input-group-text btn btn-outline-secondary">
                                Pilih
                                <input type="file" wire:model="featuredImage" class="d-none"
                                    @change="fileName = $event.target.files.length ? $event.target.files[0].name : 'Pilih foto untuk thumbnail berita'">
                            </label>
                        </div>

                        @error('featuredImage')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror

                        @if ($featuredImage)
                            <div class="mt-2">
                                <p class="small text-muted">Preview:</p>
                                <img src="{{ $featuredImage->temporaryUrl() }}" alt="Preview"
                                    class="img-fluid rounded shadow-sm" style="height: 150px; object-fit: cover;">
                            </div>
                        @endif
                    </div>

                    <!-- Kategori -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bidang Pelaksana</label>
                        <select wire:model="categoryId" wire:model="post.category_id" class="form-select">
                            <option value="">-- Pilih Bidang --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tags -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tags Berita</label>
                        <div class="d-flex flex-column gap-1">
                            @foreach ($tags as $tag)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $tag->id }}"
                                        wire:model="tagIds">
                                    <label class="form-check-label">{{ $tag->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Status Publikasi -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Publikasi</label>
                        <select wire:model="status" wire:model="post.status" class="form-select">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                </div>
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

        const contentInput = document.querySelector('#content');

        // Update hidden input saat Quill berubah
        quill.on('text-change', function() {
            const html = quill.root.innerHTML;
            contentInput.value = html;

            contentInput.dispatchEvent(new Event('input'));
        });

        // Kalau mau set value awal dari Livewire ke Quill
        Livewire.hook('message.processed', (message, component) => {
            if (@this.content && quill.root.innerHTML !== @this.content) {
                quill.root.innerHTML = @this.content;
            }
        });

        // handle image upload
        quill.getModule('toolbar').addHandler('image', function() {
            @this.set('content', quill.root.innerHTML);

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
