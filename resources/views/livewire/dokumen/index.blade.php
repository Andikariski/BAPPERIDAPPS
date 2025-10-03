@section('title', 'Admin | Berita')
<div class="container py-2">

    <!-- Search & filtering -->
    <div class="d-flex justify-content-center">
        <!-- Section Title -->
        <section id="team" class="team section w-100">
            <div class="container section-title text-center" data-aos="fade-up">
                <h2>DOKUMEN PUBLIK</h2>
                <p>Dokumen yang telah di terbitkan oleh BAPPERIDA PPS</p>
            </div>
            <!-- End Section Title -->
            <div class="p-4">
                <div class="container">
                    <div class="row gy-4">
                        <div class="col-6">
                            <input wire:model.live.debounce.500ms="searchDokumen" type="text" class="form-control"
                                id="exampleFormControlInput1" placeholder="Cari Dokumen">
                        </div>
                        <div class="col-6">
                            <select wire:model.live="filterBidang" class="form-select"
                                aria-label="Default select example">
                                <option selected>-Semua Bidang-</option>
                                @foreach ($dataBidang as $bidang)
                                    <option value="{{ $bidang->id }}">{{ $bidang->nama_bidang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <br>

                <div class="container">
                    @forelse ($dataDokumen as $dokumen)
                        <div class="card shadow border-0 rounded-3 p-3">
                            <div class="row align-items-center g-4">
                                <!-- Gambar -->
                                <div class="col-md-3 text-center">
                                    <img src="{{ Storage::url($dokumen->thumbnail_path) }}" alt="thumbnail file"
                                        class="img-fluid" style="max-width:150px;">
                                </div>

                                <!-- Konten -->
                                <div class="col-md-9">
                                    <h5 class="fw-bold mb-3 text-uppercase">{{ $dokumen->nama_dokumen }}</h5>

                                    <div class="row mb-1">
                                        <div class="col-3 col-md-2 fw-semibold">Kategori</div>
                                        <div class="col-auto px-0">:</div>
                                        <div class="col">RPJMD</div>
                                    </div>

                                    <div class="row mb-1">
                                        <div class="col-3 col-md-2 fw-semibold">Bidang</div>
                                        <div class="col-auto px-0">:</div>
                                        <div class="col">{{ $dokumen->bidang->nama_bidang ?? '-belum ditentukan-' }}
                                        </div>
                                    </div>

                                    <div class="row mb-1">
                                        <div class="col-3 col-md-2 fw-semibold">Tahun</div>
                                        <div class="col-auto px-0">:</div>
                                        <div class="col">{{ $dokumen->created_at->format('Y') }}</div>
                                    </div>

                                    <div class="row mb-1">
                                        <div class="col-3 col-md-2 fw-semibold">Tanggal Upload</div>
                                        <div class="col-auto px-0">:</div>
                                        <div class="col">{{ $dokumen->created_at->format('d M Y') }}</div>
                                    </div>

                                    <!-- Tombol -->
                                    <div class="d-flex gap-2">
                                        <button
                                            wire:click="download('{{ $dokumen->file_path }}','{{ $dokumen->file_name }}')"
                                            class="btn btn-primary">
                                            <i class="bi bi-download me-2"></i>
                                            Download
                                        </button>
                                        <a href="#" class="btn btn-secondary px-4"><i
                                                class="bi bi-eye me-2"></i>Preview</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning text-center">
                            <p class="my-3">dokumen masih kosong</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
    <!-- Articles -->
</div>
