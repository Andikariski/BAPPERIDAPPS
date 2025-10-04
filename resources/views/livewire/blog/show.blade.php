<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $berita->judul_berita }}</li>
        </ol>
    </nav>
    <div class="pt-4">
        <div class="">
        <img src="{{ Storage::url('foto_thumbnail_berita/' . $berita->foto_thumbnail) }}"
                            class="card-img-top mb-4" style="height: 550px; object-fit: cover;"
                            alt="{{ $berita->judul_berita }}">
                  
            <h3>{{ $berita->judul_berita }}</h3>
            <p class="text-muted">
                Bidang Pelaksana: {{ $berita->bidang->nama_bidang ?? 'tidak terkategori' }}
                | By {{ $berita->author->name }}
            </p>
        </div>
        <div>{!! $berita->konten_berita !!}</div>
    </div>
</div>
