<div>
    @php
        $breadcrumbs = [
            ['name' => 'Beranda', 'url' => route('dashboard')],
            ['name' => 'Artikel', 'url' => route('admin.posts.index')],
            ['name' => 'Buat Artikel Baru'],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />

    <div class="mt-10">
        <form wire:submit.prevent="{{ isset($post) ? 'update' : 'save' }}">
            <div class="grid grid-cols-12 gap-20">
                <div class="col-span-12 lg:col-span-8 space-y-10">
                    <div class="space-y-2">
                        <label class="block font-medium">Judul Artikel</label>
                        <input type="text" wire:model="title" wire:model="post.title"
                            class="border rounded w-full px-3 py-2">
                        @error('title')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="space-y-2" wire:ignore>
                        <label class="block font-medium">Konten</label>
                        <div class="h-[550px] relative overflow-auto border-b border-b-gray-300">
                            <div id="editor" class="border rounded"></div>
                        </div>
                        <input type="hidden" wire:model="content" id="content">
                        @error('content')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="text-white px-4 py-2 rounded bg-blue-600">
                        Simpan Artikel
                    </button>
                </div>
                <div class="col-span-12 lg:col-span-4 space-y-10">
                    <div class="space-y-2" x-data="{ fileName: 'Pilih foto untuk thumbnail artikel' }">
                        <label class="block font-medium text-gray-700">Foto Thumbnail Artikel</label>

                        <!-- Custom File Upload -->
                        <label
                            class="flex w-full px-4 py-2 bg-white border rounded-lg shadow cursor-pointer hover:bg-gray-50">
                            <span class="text-gray-600" x-text="fileName"></span>
                            <input type="file" wire:model="featuredImage" class="hidden"
                                @change="fileName = $event.target.files.length ? $event.target.files[0].name : 'Pilih foto untuk thumbnail artikel'">
                        </label>

                        @error('featuredImage')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        <!-- Preview -->
                        @if ($featuredImage)
                            <div class="mt-3">
                                <p class="text-sm text-gray-600">Preview:</p>
                                <img src="{{ $featuredImage->temporaryUrl() }}" alt="Preview"
                                    class="w-full h-[150px] object-cover rounded-lg shadow">
                            </div>
                        @endif
                    </div>
                    <div class="space-y-2">
                        <label class="block font-medium">Kategori Artikel</label>
                        <select wire:model="categoryId" wire:model="post.category_id"
                            class="border rounded w-full px-3 py-2">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block font-medium">Tags Artikel</label>
                        <div class="flex flex-col gap-2">
                            @foreach ($tags as $tag)
                                <label>
                                    <input type="checkbox" value="{{ $tag->id }}" wire:model="tagIds">
                                    {{ $tag->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block font-medium">Status Publikasi</label>
                        <select wire:model="status" wire:model="post.status" class="border rounded w-full px-3 py-2">
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
