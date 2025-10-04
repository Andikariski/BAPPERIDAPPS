@section('title', 'Admin | Berita')
<div class="container py-2">
    <div class="container section-title mt-5" data-aos="fade-up" wire:ignore>
        <h2>Berita</h2>
        <p>Segala Berita Tentang Kegiatan dan Aktivitas BAPPERIDA PPS</p>
    </div>

    <!-- Search & filtering -->
    <div class="d-flex justify-content-between gap-2">
        <input type="text" class="form-control mb-3" placeholder="cari berita..."
            wire:model.live.debounce.500ms="search">
        <div class="mb-3">
            <select wire:model.live="selectedBidang" class="form-select">
                <option class="px-4" value="">Pilih Bidang</option>
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
                <div class="card h-100 shadow-sm">
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
                        <div class="mb-3">
                            <a href="{{ route('blog.show', $post->slug) }}"
                                class="card-title text-bold text-primary fs-4"><strong>{{ $post->judul_berita }}</strong></a>
                            <p>{{ Str::limit(strip_tags($post->konten_berita), 100) }}</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted"
                                    style="font-size: 0.90rem;">{{ $post->created_at->diffForHumans() }}</span>
                                <span class="text-muted" style="font-size: 0.90rem;">Oleh
                                    {{ $post->author->name }}</span>
                            </div>
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

    <div class="mt-4">
        {{ $posts->links('vendor.livewire.bootstrap-pagination') }}
    </div>
</div>
