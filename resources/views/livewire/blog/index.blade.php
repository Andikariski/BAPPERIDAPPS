<div class="container py-5">
    <h1 class="mb-4">Blog</h1>

    <!-- Search & filtering -->
    <div class="d-flex justify-content-between gap-2">
        <input type="text" class="form-control mb-3" placeholder="cari artikel..." wire:model.live="search">
        <div class="mb-3">
            <select wire:model.live="category" class="form-select">
                <option class="px-4" value="">Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!-- Articles -->
    <div class="row">
        @forelse($posts as $post)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if ($post->featured_image)
                        <img src="{{ $post->featured_image }}" class="card-img-top" alt="{{ $post->title }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ $post->excerpt }}</p>
                        <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-primary">Selengkapnya</a>
                    </div>
                </div>
            </div>
        @empty
            <p>No posts found.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $posts->links('vendor.livewire.bootstrap-pagination') }}
    </div>

</div>
