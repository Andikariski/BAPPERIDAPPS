<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
        </ol>
    </nav>
    <div class="pt-4">
        <h1>{{ $post->title }}</h1>
        <p class="text-muted">
            Category: {{ $post->category->name ?? 'Uncategorized' }}
            | By {{ $post->author->name }}
        </p>
        <div>{!! $post->content !!}</div>
    </div>
</div>
