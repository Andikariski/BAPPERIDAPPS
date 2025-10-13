<div>
    @php
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Data berita', 'url' => route('admin.berita.index')],
            ['name' => 'Detail Data berita'],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />

    <div class="mt-3">
        <div class="row">
            <div class="col-12 col-lg-7 bg-light rounded p-4 overflow-hidden">
                <p class="text-dark">{!! $berita->konten_berita !!}</p>
            </div>
            <div class="col-12 col-lg-4">
                <p class="fw-semibold text-muted">Foto Thumbnail</p>
                <div class="overflow-hidden rounded bg-light d-flex align-items-center justify-content-center"
                    style="height: 200px;">
                    @if ($berita->foto_thumbnail)
                        <img src="{{ Storage::url('foto_thumbnail_berita/' . $berita->foto_thumbnail) }}" alt="thumbnail"
                            style="object-fit: cover; width: 100%;">
                    @else
                        <div class="d-flex flex-column align-items-center">
                            <i class="bi bi-image fs-1 text-dark"></i>
                            <p class="text-center text-dark">foto thumbnail belum ada</p>
                        </div>
                    @endif
                </div>
                <div class="mt-4">
                    <div class="my-1">
                        <p class="fw-semibold m-0 text-muted">Judul Berita</p>
                        <p class="m-0 fs-5 text-dark">{{ $berita->judul_berita }}</p>
                    </div>
                    <div class="my-1">
                        <p class="fw-semibold m-0 text-muted">Bidang Pelaksana</p>
                        <p class="m-0 fs-5 text-dark">{{ $berita->bidang->nama_bidang }}</p>
                    </div>
                    <div class="my-1">
                        <p class="fw-semibold m-0 text-muted">Tanggal Ditulis</p>
                        <p class="m-0 fs-5 text-dark">
                            {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <div class="my-1">
                        <p class="fw-semibold m-0 text-muted">Status Publikasi</p>
                        <p
                            class="fw-bold {{ $berita->status_publikasi == 'published' ? 'text-success' : 'text-dark' }}">
                            {{ $berita->status_publikasi }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-1"></div>
        </div>
    </div>
</div>
