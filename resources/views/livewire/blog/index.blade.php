@section('title', 'Admin | Berita')
<div class="container py-5">
    <h2 class="mb-4 text-center">Berita</h2>

    <!-- Search & filtering -->
    <div class="d-flex justify-content-between gap-2">
        <input type="text" class="form-control mb-3" placeholder="cari berita..." wire:model.live="search">
        <div class="mb-3">
            <select wire:model.live="bidang" class="form-select">
                <option class="px-4" value="">Kategori</option>
                @foreach ($dataBidang as $bidang)
                    <option value="{{ $bidang->id }}">{{ $bidang->nama_bidang }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!-- Articles -->
    <div class="row">
        @forelse($posts as $post)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if ($post->foto_thumbnail)
                        <img src="{{ Storage::url('foto_thumbnail_berita/' . $post->foto_thumbnail) }}"
                            class="card-img-top" style="height: 200px; object-fit: cover;"
                            alt="{{ $post->judul_berita }}">
                    @else
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-secondary text-white"
                            style="height: 200px;">
                            <span class="text-sm">No Image</span>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->judul_berita }}</h5>
                        {{-- <p class="card-text">{{ $post->created_at }}</p> --}}
                        <p class="card-text">{{ $post->created_at->translatedFormat('j F Y') }}</p>

                        <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-primary">Selengkapnya</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-warning d-flex align-items-center justify-content-center">
                <p class="my-5">No posts found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $posts->links('vendor.livewire.bootstrap-pagination') }}
    </div>

</div>
