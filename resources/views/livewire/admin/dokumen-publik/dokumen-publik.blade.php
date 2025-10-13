<div>
    @php
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Data Dokumen Publik', 'url' => route('admin.dokumenpublik.index')],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />

    <div class="my-4">
        <div class="d-flex justify-content-between align-items-center mb-1 mt-4">
            <div class="row g-3">
                <div class="col-lg-6">
                    <input type="text" wire:model.live.debounce.500ms="search" placeholder="Cari Dokumen..."
                        class="form-control">
                </div>
                <div class="col-lg-6">
                    <select wire:model.live="filterBidang" class="form-select">
                        <option value="">Semua Bidang</option>
                        @foreach ($dataBidang as $bidang)
                            <option value="{{ $bidang->id }}">{{ $bidang->nama_bidang }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <a href="{{ route('admin.dokumenpublik.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Dokumen</span>
            </a>
        </div>

        <div>
            {{-- Grid Card Dokumen --}}
            <div class="row g-2 mt-3">
                @forelse($dataDokumen as $dokumen)
                    <div class="col-md-3">
                        <div class="h-100 shadow-sm overflow-hidden rounded">
                            {{-- Thumbnail --}}
                            <div class="overflow-hidden" style="height: 250px;">
                                @if ($dokumen->thumbnail_path)
                                    <img src="{{ asset('storage/' . $dokumen->thumbnail_path) }}" class="card-img-top"
                                        alt="{{ $dokumen->nama_dokumen }}">
                                @else
                                    <div class="icon-box text-center">
                                        <i class="bi bi-journal-bookmark-fill" style="font-size:180px; color:#296cc5; text-shadow: 3px 3px 6px rgba(0,0,0,0.25); "></i>
                                    </div>
                                @endif
                            </div>

                            <div class="p-3">
                                <div class="mb-3 d-flex flex-column gap-1">
                                    <h5 class="card-title">{{ $dokumen->nama_dokumen }}</h5>
                                    <span class="text-dark">
                                        {{ $dokumen->bidang?->nama_bidang ?? '-belum ditentukan-' }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a wire:navigate href="{{ route('admin.dokumenpublik.detail', $dokumen->id) }}"
                                        title="lihat file" class="btn btn-sm btn-outline-dark">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a wire:navigate href="{{ route('admin.dokumenpublik.edit', $dokumen->id) }}"
                                        title="edit file" class="btn btn-outline-dark btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button wire:click="$dispatch('confirm-delete-data-dokumen', {{ $dokumen }})"
                                        title="hapus file" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center">Tidak ada dokumen ditemukan.</div>
                    </div>
                @endforelse
            </div>
        </div>
        <!-- Pagination -->
    </div>
    <div class="mt-4 mb-4">
        {{ $dataDokumen->links('vendor.livewire.bootstrap-pagination') }}
    </div>
</div>
