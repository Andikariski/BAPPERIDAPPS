<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $berita->judul_berita }}</li>
        </ol>
    </nav>

<div class="row g-4">
    <!-- Kolom Kiri -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-4">
                    <img src="{{ Storage::url('foto_thumbnail_berita/' . $berita->foto_thumbnail) }}"
                            class="card-img-top mb-4" style="height: 550px; object-fit: cover;"
                            alt="{{ $berita->judul_berita }}">
                    <h3>{{ $berita->judul_berita }}</h3>
                    <p class="text-muted">
                        Bidang Pelaksana: {{ $berita->bidang->nama_bidang ?? 'tidak terkategori' }}
                        | By {{ $berita->author->name }}
                    </p>
                </div>
                <div class="mt-2">{!! $berita->konten_berita !!}</div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-4">Berita Terbaru</h5>
               @forelse ($beritaTerbaru as $item)
                <div class="card border-0 mb-3">
                    <div class="row g-0 align-items-center">
                    <div class="col-3">
                        <div class="thumbnail-wrapper">
                            <img src="{{ Storage::url('foto_thumbnail_berita/'.$item->foto_thumbnail) }}" class="img-fluid rounded-start">
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="py-2 px-3">
                            <a href="{{ route('blog.show', $item->slug) }}"
                                class="card-title text-bold text-primary"><strong>{{ Str::limit(strip_tags($item->judul_berita),60) }}</strong></a>
                            <p class="card-text">
                                <small class="text-muted fst-italic">
                                    {{ $item->created_at->diffForHumans() }}
                                </small>
                            </p>
                        </div>
                    </div>
                    </div>
                </div>
        @empty
            <div class="alert alert-warning d-flex align-items-center justify-content-center">
                <p class="my-5">tidak ada berita ditemukan</p>
            </div>
        @endforelse

            </div>
        </div>
    </div>
</div>


</div>
