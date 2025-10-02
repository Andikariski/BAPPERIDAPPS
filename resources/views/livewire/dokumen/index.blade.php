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
                    <div class="col-5">
                        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Cari Dokumen">
                    </div>
                    <div class="col-5">
                        <select class="form-select" aria-label="Default select example">
                            <option selected>Bidang</option>
                            <option value="1">Bidang Perencanaan</option>
                            <option value="2">Bidang Ekonomi Sosial Budaya</option>
                            <option value="3">Bidang Fisik dan prasarana</option>
                            <option value="3">Bidang Riset dan Inovasi</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <select class="form-select" aria-label="Default select example">
                            <option selected>Tahun Dokumen</option>
                            <option value="1">2024</option>
                            <option value="2">2025</option>
                            <option value="3">2026</option>
                        </select>
                    </div>
                </div> 
            </div>
            <br>

            <div class="container">
                <div class="card shadow border-0 rounded-3 p-3">
                    <div class="row align-items-center g-4">
                    
                    <!-- Gambar -->
                    <div class="col-md-3 text-center">
                        {{-- <img src="book.png" alt="Dokumen Icon" class="img-fluid" style="max-width:150px;"> --}}
                       <div class="icon-box">
                            <i class="bi bi-journal-bookmark-fill" style="font-size:180px; color:#296cc5; text-shadow: 3px 3px 6px rgba(0,0,0,0.25); "></i>
                        </div>
                    </div>

                    <!-- Konten -->
                    <div class="col-md-9">
                       <h5 class="fw-bold mb-3">RENCANA PEMBANGUNAN JANGKA MENENGAH DAERAH</h5>

                        <div class="row mb-1">
                        <div class="col-3 col-md-2 fw-semibold">Kategori</div>
                        <div class="col-auto px-0">:</div>
                        <div class="col">RPJMD</div>
                        </div>

                        <div class="row mb-1">
                        <div class="col-3 col-md-2 fw-semibold">Bidang</div>
                        <div class="col-auto px-0">:</div>
                        <div class="col">Ekonomi Sosial Budaya</div>
                        </div>

                        <div class="row mb-1">
                        <div class="col-3 col-md-2 fw-semibold">Tahun</div>
                        <div class="col-auto px-0">:</div>
                        <div class="col">2023</div>
                        </div>

                        <div class="row mb-1">
                        <div class="col-3 col-md-2 fw-semibold">Tanggal Upload</div>
                        <div class="col-auto px-0">:</div>
                        <div class="col">28-Jul-2025</div>
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex gap-2">
                        <a href="#" class="btn btn-primary px-4"><i class="bi bi-download me-2"></i>Download</a>
                        <a href="#" class="btn btn-secondary px-4"><i class="bi bi-eye me-2"></i>Preview</a>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>
    <!-- Articles -->
</div>
