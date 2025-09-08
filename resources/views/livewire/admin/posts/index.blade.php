<div>
    @php
        $breadcrumbs = [
            ['name' => 'Beranda', 'url' => route('dashboard')],
            ['name' => 'Artikel', 'url' => route('admin.posts.index')],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />

    <div class="d-flex justify-content-between align-items-center mb-1 mt-4">
        <input type="text" placeholder="Search..." wire:model.live="search" class="form-control w-25 rounded-1">
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary d-flex align-items-center rounded-1 gap-1">
            <i class="bi bi-pencil-square"></i>
            <span>Tulis Berita</span>
        </a>
    </div>

    <div class="rounded-1 overflow-hidden border p-0">
        <table class="table table-striped align-middle mb-0">
            <thead class="table-secondary">
                <tr>
                    <th class="px-4 py-2 text-dark">Judul Artikel</th>
                    <th class="px-4 py-2 text-dark">Kategori</th>
                    <th class="px-4 py-2 text-dark">Status Publikasi</th>
                    <th class="px-4 py-2 text-dark">Tanggal Dipublikasi</th>
                    <th class="px-4 py-2 text-dark">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td class="px-4 py-1 text-dark">{{ $post->title }}</td>
                        <td class="px-4 py-1 text-dark">{{ $post->category->name ?? '-' }}</td>
                        <td class="px-4 py-1">
                            <span style="padding: 6px 0; opacity: 70%"
                                class="rounded-1 w-50 border badge {{ $post->status == 'published' ? 'bg-success text-light' : 'bg-dark text-light' }}">
                                {{ $post->status }}
                            </span>
                        </td>
                        <td class="px-4 py-1 text-dark">
                            {{ \Carbon\Carbon::parse($post->published_at)->translatedFormat('l, d F Y') }}
                        </td>

                        <td class="px-4 py-1 d-flex gap-2">
                            <a href="{{ route('admin.posts.edit', $post) }}"
                                class="d-flex align-items-center gap-1 btn btn-sm btn-outline-dark">
                                <i class="bi bi-pencil" style="font-size: 1rem;"></i>
                                <span>Edit</span></a>
                            <!-- Tombol Buka Modal -->
                            <button wire:click="confirmDelete({{ $post->id }})" data-bs-toggle="modal"
                                data-bs-target="#deleteModal"
                                class="btn btn-sm btn-outline-dark d-flex align-items-center gap-1">
                                <i class="bi bi-trash3"></i> Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-5 text-center">
                            <div class="d-inline-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-emoji-tear text-warning" style="font-size: 60px"></i>
                                <span class="fs-5 text-dark">berita masih kosong!</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <!-- Modal Bootstrap -->
        <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Artikel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @if ($selectedPostId)
                            <p>Yakin ingin menghapus artikel <strong>{{ $selectedPostTitle }}</strong>?</p>
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div class="spinner-border spinner-border-sm text-dark" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="fs-5">memuat data...</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button wire:click="delete"
                            class="btn btn-danger d-flex align-items-center justify-content-center gap-1">
                            <div wire:loading="delete" class="spinner-border spinner-border-sm text-white"
                                role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove="delete">Hapus Artikel</span>
                            <span wire:loading="delete">Menghapus...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $posts->links('vendor.livewire.bootstrap-pagination') }}
    </div>
</div>
