<div>
    @php
        $breadcrumbs = [
            ['name' => 'Beranda', 'url' => route('dashboard')],
            ['name' => 'Artikel', 'url' => route('admin.posts.index')],
            ['name' => 'Edit Artikel'],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />

    <div class="mt-10">

        <form wire:submit.prevent="{{ isset($post) ? 'update' : 'save' }}">
            <div class="mb-4">
                <label class="block font-medium">Title</label>
                <input type="text" wire:model="title" wire:model="post.title" class="border rounded w-full px-3 py-2">
                @error('title')
                    <span class="text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium">Slug</label>
                <input type="text" wire:model="slug" wire:model="post.slug" class="border rounded w-full px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-medium">Excerpt</label>
                <textarea wire:model="excerpt" wire:model="post.excerpt" class="border rounded w-full px-3 py-2"></textarea>
            </div>

            <div class="mb-4" wire:ignore>
                <label class="block font-medium">Content</label>
                <div class="h-[400px]">
                    <div id="editor" class="border rounded"></div>
                </div>
                <input type="hidden" wire:model="content" id="content">
                @error('content')
                    <span class="text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium">Category</label>
                <select wire:model="category_id" wire:model="post.category_id" class="border rounded w-full px-3 py-2">
                    <option value="">-- Choose --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium">Tags</label>
                <div class="flex flex-wrap gap-2">
                    @foreach ($tags as $tag)
                        <label>
                            <input type="checkbox" value="{{ $tag->id }}" wire:model="tag_ids"> {{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="block font-medium">Status</label>
                <select wire:model="status" wire:model="post.status" class="border rounded w-full px-3 py-2">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                Save
            </button>
        </form>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <script>
        document.addEventListener('livewire:load', function() {
            const quill = new Quill('#editor', {
                theme: 'snow'
            });

            quill.on('text-change', function() {
                @this.set('content', quill.root.innerHTML);
                document.getElementById('content').value = quill.root.innerHTML;
            });

            @this.on('refreshEditor', () => {
                quill.root.innerHTML = @this.get('content') ?? '';
            });
        });
    </script>
@endpush
