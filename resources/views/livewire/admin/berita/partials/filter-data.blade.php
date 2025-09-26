<div class="row justify-content-between align-items-center">
    <div class="col-12 col-md-9 d-flex align-items-center gap-3 order-first mb-1">
        <input type="text" placeholder="Cari berita..." wire:model.live.debounce.500ms="searchBerita"
            class="form-control" style="width: 250px;">

        <div class="d-flex align-items-center gap-1">
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-1" type="button"
                    data-bs-toggle="dropdown">
                    @if ($this->filterStatus === 'active')
                        <i class="bi bi-check-circle"></i> Aktif ({{ \App\Models\Berita::count() }})
                    @elseif ($this->filterStatus === 'trashed')
                        <i class="bi bi-archive"></i> Arsip ({{ \App\Models\Berita::onlyTrashed()->count() }})
                    @else
                        <i class="bi bi-collection"></i> Semua ({{ \App\Models\Berita::withTrashed()->count() }})
                    @endif
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-1" href="#"
                            wire:click="$set('filterStatus', 'active')">
                            <i class="bi bi-check-circle"></i> Aktif ({{ \App\Models\Berita::count() }})
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-1" href="#"
                            wire:click="$set('filterStatus', 'trashed')">
                            <i class="bi bi-archive"></i> Arsip ({{ \App\Models\Berita::onlyTrashed()->count() }})
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-1" href="#"
                            wire:click="$set('filterStatus', 'all')">
                            <i class="bi bi-collection"></i> Semua ({{ \App\Models\Berita::withTrashed()->count() }})
                        </a>
                    </li>
                </ul>
            </div>
            @if ($searchBerita || $filterStatus !== 'active')
                <button wire:click="resetFilters()" class="btn btn-light" title="Reset">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-3 order-last mb-1">
        <a href="{{ route('admin.berita.create') }}" class="btn btn-primary w-100" wire:navigate>
            <i class="bi bi-plus-lg"></i> Tambah Berita
        </a>
    </div>
</div>
