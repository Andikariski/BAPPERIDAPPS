<div>
    @php
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Data Pegawai', 'url' => route('admin.pegawai.index')],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />
    <div class="my-4">
        <div class="d-flex justify-content-between align-items-center mb-1 mt-4">
            <div class="row g-3">
                <div class="col-lg-4">
                    <input type="text" wire:model.live.debounce.500ms="searchPegawai" placeholder="Cari kegiatan..."
                        class="form-control">
                </div>
                <div class="col-lg-4">
                    <select wire:model.live="bidangFilter" class="form-select">
                        <option value="">Semua Bidang</option>
                        @foreach ($dataBidang as $bidang)
                            <option value="{{ $bidang->id }}">{{ $bidang->nama_bidang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4">
                    <select wire:model.live="jabatanFilter" class="form-select">
                        <option value="">Semua Jabatan</option>
                        @foreach ($dataJabatan as $jabatan)
                            <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="button" class="btn btn-primary" wire:click="openTambahModal">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Pegawai</span>
            </button>
        </div>

        <div class="rounded overflow-hidden border">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-secondary">
                    <tr>
                        <th class="px-4 py-2 text-dark">Nama pegawai</th>
                        <th class="px-4 py-2 text-dark">Nomor NIP</th>
                        <th class="px-4 py-2 text-dark">Jabatan</th>
                        <th class="px-4 py-2 text-dark">Bidang</th>
                        <th class="px-4 py-2 text-dark">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataPegawai as $pegawai)
                        <tr>
                            <td class="px-4 py-1 text-dark">{{ $pegawai->nama_pegawai }}</td>
                            <td class="px-4 py-1 text-dark">{{ $pegawai->nip }}</td>
                            <td class="px-4 py-1 text-dark">{{ $pegawai->jabatan->nama_jabatan ?? 'belum ditentukan' }}
                            </td>
                            <td class="px-4 py-1 text-dark">{{ $pegawai->bidang->nama_bidang ?? 'belum ditentukan' }}
                            </td>
                            <td class="px-4 py-1 d-flex gap-2">
                                <!-- Tombol Edit -->
                                <button wire:click="openEditModal({{ $pegawai->id }})"
                                    class="btn btn-sm btn-outline-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-pencil"></i>
                                    <span>Edit</span>
                                </button>
                                <!-- Tombol Edit -->
                                <button wire:click="openDetailModal({{ $pegawai->id }})"
                                    class="btn btn-sm btn-outline-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-eye"></i>
                                    <span>Detail</span>
                                </button>

                                <!-- Tombol Hapus -->
                                <button wire:click="$dispatch('confirm-delete-data-pegawai', {{ $pegawai }})"
                                    class="btn btn-sm btn-outline-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-trash3"></i>
                                    <span>Hapus</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-5 text-center">
                                <div class="d-inline-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-emoji-tear text-warning" style="font-size: 60px"></i>
                                    <span class="fs-5 text-dark">data pegawai masih kosong!</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- pagination --}}
        <div class="mt-4">
            {{ $dataPegawai->links('vendor.livewire.bootstrap-pagination') }}
        </div>
    </div>
    <!-- Modal -->
    @if ($this->showDetailModal)
        <x-modal :title="$modalTitle" :closeble="true" @click.self="$wire.closeModal()"
            @keydown.escape.window="$wire.closeModal()">

            <x-slot name="closeButton">
                <button type="button" class="btn-close" aria-label="Close" wire:click="closeModal">
                </button>
            </x-slot>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <small>nama Pegawai</small>
                        <p class="fs-6 fw-bold">{{ $namaPegawai }}</p>
                    </div>
                    <div class="mb-3">
                        <small>nama Pegawai</small>
                        <p class="fs-6 fw-bold">{{ $nip }}</p>
                    </div>
                    <div class="mb-3">
                        <small>Jabatan Pegawai</small>
                        <p class="fs-6 fw-bold">{{ $fkidJabatan }}</p>
                    </div>
                    <div class="mb-3">
                        <small>Bidang Pegawai</small>
                        <p class="fs-6 fw-bold">{{ $fkidBidang }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <small>foto profil</small>
                        @if ($fotoProfile)
                            <img src="{{ Storage::url('foto_profil_pegawai/' . $fotoProfile) }}" alt="Preview"
                                class="img-fluid rounded shadow-sm"
                                style="height: 300px; object-fit: cover; width: 100%;">
                        @else
                            <p>foto tidak tersedia</p>
                        @endif
                    </div>

                </div>
            </div>

            <x-slot name="footer">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light" wire:click="closeModal">
                        <span wire:loading.remove wire:target="closeModal">Tutup</span>
                        <span wire:loading wire:target="closeModal">tunggu...</span>
                    </button>
                </div>
            </x-slot>
        </x-modal>
    @endif
    @if ($this->showModal)
        <x-modal :title="$modalTitle" :closeble="true" @click.self="$wire.closeModal()"
            @keydown.escape.window="$wire.closeModal()">

            <x-slot name="closeButton">
                <button type="button" class="btn-close" aria-label="Close" wire:click="closeModal">
                </button>
            </x-slot>

            <form wire:submit.prevent="simpan">
                <div class="mb-3">
                    <label for="nip" class="form-label">
                        Nama Pegawai
                    </label>
                    <input type="text" class="form-control @error('nip') is-invalid @enderror" id="namaPegawai"
                        wire:model="namaPegawai" placeholder="Masukkan nama pegawai..." maxlength="255">
                    @error('namaPegawai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="nip" class="form-label">
                        NIP
                    </label>
                    <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip"
                        wire:model="nip" placeholder="Masukkan NIP..." maxlength="255">
                    @error('nip')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fkidJabatan">Jabatan Pegawai</label>
                    <select class="form-select" wire:model="fkidJabatan">
                        <option value="">Pilih Jabatan</option>
                        @foreach ($dataJabatan as $jabatan)
                            <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fkidBidang">Bidang</label>
                    <select class="form-select" wire:model="fkidBidang">
                        <option value="">Pilih Bidang</option>
                        @foreach ($dataBidang as $bidang)
                            <option value="{{ $bidang->id }}">{{ $bidang->nama_bidang }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- foto profile -->
                <div class="mb-3" x-data="{ fileName: 'Pilih foto pegawai' }">
                    <label class="form-label fw-semibold">Foto Thumbnail Berita</label>

                    <div class="input-group">
                        <input type="text" class="form-control" x-model="fileName" readonly>
                        <label class="input-group-text btn btn-outline-secondary">
                            Pilih
                            <input type="file" wire:model="fotoProfile" class="d-none" accept="image/*"
                                @change="fileName = $event.target.files.length ? $event.target.files[0].name : 'Pilih foto untuk thumbnail berita'">
                        </label>
                    </div>

                    @error('fotoProfile')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror

                    <!-- Preview for new upload -->
                    @if ($fotoProfile)
                        <div class="mt-2">
                            <p class="small text-muted">Preview:</p>
                            <img src="{{ $fotoProfile->temporaryUrl() }}" alt="Preview"
                                class="img-fluid rounded shadow-sm"
                                style="height: 150px; object-fit: cover; width: 100%;">
                        </div>
                    @elseif($isEdit && $existingFotoProfile)
                        <!-- Preview for existing thumbnail -->
                        <div class="mt-2">
                            <p class="small text-muted">foto profil saat ini:</p>
                            <img src="{{ Storage::url('foto_profil_pegawai/' . $existingFotoProfile) }}"
                                alt="Current Thumbnail" class="img-fluid rounded shadow-sm"
                                style="height: 150px; object-fit: cover; width: 100%;">
                        </div>
                    @endif
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
