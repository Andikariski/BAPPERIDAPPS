<div>
    @php
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Artikel', 'url' => route('admin.posts.index')],
            ['name' => 'Edit Artikel'],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />

    <div class="mt-4">
        <form wire:submit.prevent="update">
            <div class="row g-4">
                <!-- Kolom Kiri -->
                <div class="col-12 col-lg-8">
                    <!-- Judul Artikel -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Artikel</label>
                        <input type="text" wire:model="post.title" class="form-control">
                        @error('post.title')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Konten Artikel -->
                    <div class="mb-3" wire:ignore>
                        <label class="form-label fw-semibold">Konten</label>
                        <div class="border rounded" style="height: 550px; overflow: auto;">
                            <div id="editor"></div>
                        </div>
                        <input type="hidden" wire:model="post.content" id="content">
                        @error('post.content')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                        <span wire:loading>
                            <div class="spinner-border spinner-border-sm text-dark" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </span>
                        <span wire:loading.remove>
                            Simpan Perubahan
                        </span>
                        <span wire:loading>
                            Menyimpan Perubahan...
                        </span>
                    </button>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-12 col-lg-4 order-1">
                    <!-- Thumbnail -->
                    <div class="mb-3" x-data="{
                        fileName: '{{ $post['featured_image'] ?? 'Pilih foto untuk thumbnail artikel' }}'
                    }">
                        <label class="form-label fw-semibold">Foto Thumbnail Artikel</label>

                        <div class="input-group">
                            <input type="text" class="form-control" x-model="fileName" readonly>
                            <label class="input-group-text btn btn-outline-secondary">
                                Pilih
                                <input type="file" wire:model="featuredImage" class="d-none"
                                    @change="fileName = $event.target.files.length ? $event.target.files[0].name : '{{ $post['featured_image'] ?? 'Pilih foto untuk thumbnail artikel' }}'">
                            </label>
                        </div>

                        @error('featuredImage')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror

                        {{-- Preview --}}
                        <div class="mt-2" x-data>
                            <p class="small text-muted">Preview:</p>
                            {{-- Kalau ada file baru --}}
                            @if (isset($featuredImage))
                                <img src="{{ $featuredImage->temporaryUrl() }}" alt="Preview"
                                    class="img-fluid rounded shadow-sm" style="height: 150px; object-fit: cover;">
                                {{-- Kalau tidak ada file baru tapi ada data lama --}}
                            @elseif(!empty($post['featured_image']))
                                <img src="{{ asset('storage/blog_cover_photo/' . $post['featured_image']) }}"
                                    alt="Preview" class="img-fluid rounded shadow-sm"
                                    style="height: 150px; object-fit: cover;">
                            @endif
                        </div>
                    </div>

                    <!-- Kategori -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori Artikel</label>
                        <select wire:model="post.category_id" class="form-select">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tags -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tags Artikel</label>
                        <div class="d-flex flex-column gap-1">
                            @foreach ($tags as $tag)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $tag->id }}"
                                        wire:model="tagIds" wire:key="tag-{{ $tag->id }}">
                                    <label class="form-check-label">{{ $tag->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Status Publikasi -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Publikasi</label>
                        <select wire:model="post.status" class="form-select">
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
                    [{
                        'align': []
                    }],
                    ['clean']
                ]
            }
        });

        const contentInput = document.querySelector('#content');

        // Update hidden input setiap ada perubahan di Quill
        quill.on('text-change', function() {
            const html = quill.root.innerHTML;
            contentInput.value = html;
            contentInput.dispatchEvent(new Event('input'));
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

        // 🔥 Handler upload gambar
        quill.getModule('toolbar').addHandler('image', function() {
            @this.set('post.content', quill.root.innerHTML);

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
                    reader.readAsDataURL(file);
                }
            };
        });

        // 🔥 Tracking gambar yang dihapus
        let previousImages = [];

        quill.on('text-change', function() {
            var currentImages = [];
            quill.container.firstChild.querySelectorAll('img').forEach(function(img) {
                currentImages.push(img.src);
            });

            var removedImages = previousImages.filter(function(image) {
                return !currentImages.includes(image);
            });

            removedImages.forEach(function(image) {
                @this.deleteImage(image);
                console.log('Image removed:', image);
            });

            previousImages = currentImages;
        });

        // 🔥 Event Livewire untuk menambahkan gambar ke Quill
        Livewire.on('blog-image-uploaded', function(imagePaths) {
            if (Array.isArray(imagePaths) && imagePaths.length > 0) {
                var imagePath = imagePaths[0];
                if (imagePath && imagePath.trim() !== '') {
                    var range = quill.getSelection(true);
                    quill.insertText(range ? range.index : quill.getLength(), '\n', 'user');
                    quill.insertEmbed(range ? range.index + 1 : quill.getLength(), 'image', imagePath);
                }
            }
        });
    </script>
@endscript
