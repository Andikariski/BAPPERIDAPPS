<div>
    @php
        $breadcrumbs = [
            ['name' => 'Beranda', 'url' => route('dashboard')],
            ['name' => 'Artikel', 'url' => route('admin.posts.index')],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />

    <div class="d-flex justify-content-between align-items-center mb-1 mt-4">
        <input type="text" placeholder="Search..." wire:model.live="search" class="form-control w-25">
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary d-flex align-items-center gap-1">
            <i class="bi bi-pencil-square"></i>
            <span>Tulis Berita</span>
        </a>
    </div>

    <table class="table table-striped table-hover table-bordered align-middle rounded-2 overflow-hidden">
        <thead class="table-dark">
            <tr>
                <th class="px-4 py-2">Judul Artikel</th>
                <th class="px-4 py-2">Kategori</th>
                <th class="px-4 py-2">Status Publikasi</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td class="px-4 py-1">{{ $post->title }}</td>
                    <td class="px-4 py-1">{{ $post->category->name ?? '-' }}</td>
                    <td class="px-4 py-1">{{ ucfirst($post->status) }}</td>
                    <td class="px-4 py-1">
                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-primary">Edit</a>
                        <button wire:click="delete({{ $post->id }})" class="btn btn-sm btn-danger">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $posts->links('vendor.livewire.bootstrap-pagination') }}
    </div>
</div>
