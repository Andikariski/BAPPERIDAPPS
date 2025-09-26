<div>
    @php
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Data berita', 'url' => route('admin.berita.index')],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />
    <div class="my-4">
        @include('livewire.admin.berita.partials.filter-data')
        <div class="rounded overflow-hidden border mt-1">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-secondary">
                    <tr>
                        <th class="px-4 py-2 text-dark">Judul berita</th>
                        <th class="px-4 py-2 text-dark d-none d-lg-table-cell">Bidang Pelaksana</th>
                        <th class="px-4 py-2 text-dark d-none d-xl-table-cell">Status Publikasi</th>
                        <th class="px-4 py-2 text-dark d-none d-xl-table-cell">Tanggal Ditulis</th>
                        <th class="px-4 py-2 text-dark">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataBerita as $berita)
                        <tr class="{{ $berita->deleted_at ? 'table-light' : '' }}"
                            onclick="Livewire.navigate('{{ route('admin.berita.detail', $berita) }}')"
                            style="cursor: pointer;">
                            <td
                                class="px-4 py-1 text-dark {{ $berita->deleted_at ? 'text-decoration-line-through' : '' }}">
                                {{ $berita->judul_berita }}</td>
                            <td
                                class="px-4 py-1 text-dark d-none d-lg-table-cell {{ $berita->deleted_at ? 'text-decoration-line-through' : '' }}">
                                {{ $berita->bidang->nama_bidang }}
                            </td>
                            <td class="px-4 py-1 text-dark d-none d-xl-table-cell">
                                <span style="padding: 6px 0; opacity: 70%"
                                    class="rounded-1 w-50 border badge {{ $berita->status_publikasi == 'published' ? 'bg-success text-light' : 'bg-dark text-light' }}">
                                    {{ $berita->status_publikasi }}
                                </span>
                            </td>
                            <td
                                class="px-4 py-1 text-dark d-none d-xl-table-cell {{ $berita->deleted_at ? 'text-decoration-line-through' : '' }}">
                                {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('l, d F Y') }}
                            </td>
                            <td class="px-4 py-1 d-flex gap-1">
                                @if ($berita->trashed())
                                    <button onclick="event.stopPropagation()"
                                        wire:click="$dispatch('confirm-restore-data-berita', {{ $berita }})"
                                        class="btn btn-sm btn-outline-dark d-flex align-items-center gap-1"
                                        title="Pulihkan">
                                        <i class="bi bi-arrow-clockwise"></i>
                                        <span class="d-none d-md-block">Retore</span>

                                    </button>

                                    <button onclick="event.stopPropagation()"
                                        wire:click="$dispatch('confirm-force-delete-data-berita', {{ $berita }})"
                                        class="btn btn-sm btn-outline-dark d-flex align-items-center gap-1"
                                        title="Hapus Permanen">
                                        <i class="bi bi-x-circle"></i>
                                        <span class="d-none d-md-block">Hapus</span>
                                    </button>
                                @else
                                    {{-- Actions untuk data aktif --}}
                                    <a onclick="event.stopPropagation(); Livewire.navigate('{{ route('admin.berita.edit', $berita->id) }}')"
                                        class="btn btn-sm btn-outline-dark d-flex align-items-center gap-1"
                                        wire:navigate title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                        <span class="d-none d-md-block">Edit</span>
                                    </a>

                                    <button onclick="event.stopPropagation()"
                                        wire:click="$dispatch('confirm-soft-delete-data-berita', {{ $berita }})"
                                        class="btn btn-sm btn-outline-dark d-flex align-items-center gap-1"
                                        title="Arsipkan">
                                        <i class="bi bi-trash"></i>
                                        <span class="d-none d-md-block">Arsipkan</span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-5 text-center">
                                <div class="d-inline-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-emoji-tear text-warning" style="font-size: 60px"></i>
                                    <span class="fs-5 text-dark">data berita masih kosong!</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- pagination --}}
        <div class="mt-4">
            {{ $dataBerita->links('vendor.livewire.bootstrap-pagination') }}
        </div>
    </div>
</div>
