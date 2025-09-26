<div x-cloak>
    @php
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Data Bidang', 'url' => route('admin.bidang.index')],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />
    <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center mb-1 mt-4">
            <input type="text" placeholder="Search..." wire:model.live.debounce.500ms="searchBidang"
                class="form-control w-25 rounded-1">
            <button type="button" class="btn btn-primary" wire:click="openTambahModal">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Bidang</span>
            </button>
        </div>

        <div class="rounded overflow-hidden border">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-secondary">
                    <tr>
                        <th class="px-4 py-2 text-dark">Nama Bidang</th>
                        <th class="px-4 py-2 text-dark">Tanggal Dibuat</th>
                        <th class="px-4 py-2 text-dark">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataBidang as $bidang)
                        <tr>
                            <td class="px-4 py-1 text-dark">{{ $bidang->nama_bidang }}</td>
                            <td class="px-4 py-1 text-dark">
                                {{ \Carbon\Carbon::parse($bidang->created_at)->translatedFormat('l, d F Y') }}
                            </td>
                            <td class="px-4 py-1 d-flex gap-2">
                                <!-- Tombol Edit -->
                                <button wire:click="openEditModal({{ $bidang->id }})"
                                    class="btn btn-sm btn-outline-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-pencil"></i>
                                    <span>Edit</span>
                                </button>

                                <!-- Tombol Hapus -->
                                <button wire:click="$dispatch('confirm-delete-data-bidang', {{ $bidang }})"
                                    class="btn btn-sm btn-outline-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-trash3"></i>
                                    <span>Hapus</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-5 text-center">
                                <div class="d-inline-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-emoji-tear text-warning" style="font-size: 60px"></i>
                                    <span class="fs-5 text-dark">data bidang masih kosong!</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{-- pagination --}}
    <div class="mt-4">
        {{ $dataBidang->links('vendor.livewire.bootstrap-pagination') }}
    </div>

    <!-- Modal -->
    @if ($this->showModal)
        <x-modal :title="$modalTitle" :closeble="true" @click.self="$wire.closeModal()"
            @keydown.escape.window="$wire.closeModal()">

            <x-slot name="closeButton">
                <button type="button" class="btn-close" aria-label="Close" wire:click="closeModal">
                </button>
            </x-slot>

            <form wire:submit.prevent="simpan">
                <div class="mb-3">
                    <label for="namaBidang" class="form-label">
                        Nama Bidang <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('namaBidang') is-invalid @enderror" id="namaBidang"
                        wire:model="namaBidang" placeholder="Masukkan nama bidang..." maxlength="255">
                    @error('namaBidang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </form>

            <x-slot name="footer">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light" wire:click="closeModal">
                        <span wire:loading.remove wire:target="closeModal">Batal</span>
                        <span wire:loading wire:target="closeModal">tunggu...</span>
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="simpan" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="simpan">
                            {{ $isEdit ? 'Perbarui' : 'Simpan' }}
                        </span>
                        <span wire:loading wire:target="simpan">
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </x-slot>
        </x-modal>
    @endif
</div>
