@section('title', 'Admin | Berita')

<div class="container">
 <div class="banner-crop-full">
        <img src="{{ asset('assets/img/element5.png') }}" alt="Banner Pegawai">
    </div>
    <div class="container section-title" data-aos="fade-up">
        <h2>Pegawai</h2>
        <p>Pegawai Bapperida Provinsi Papua Selatan</p>
    </div>

    <section id="team" class="team section-bg">
        <!-- Section Title -->
        <!-- End Section Title -->

        <div class="container mt-2">
            <div class="row gy-4">

                @forelse ($dataPegawai as $pegawai)
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="team-member">
                            <div class="member-img" style="height: 350px">
                                <img src="{{ Storage::url('foto_profil_pegawai/' . $pegawai->foto_profile) }}"
                                    class="h-100 w-100" style="object-fit: cover" />
                            </div>

                            <div class="member-info">
                                <h4>{{ $pegawai->nama_pegawai }}</h4>
                                <span>
                                    {{ $pegawai->jabatan->nama_jabatan ?? '-belum ditentukan-' }}
                                    {{ $pegawai->bidang->nama_bidang ?? '' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning text-center">
                        <p>Data pegawai masih kosong</p>
                    </div>
                @endforelse

            </div>

            <div class="mt-4">
                {{ $dataPegawai->links('vendor.livewire.bootstrap-pagination') }}
            </div>

        </div>
    </section>

</div>