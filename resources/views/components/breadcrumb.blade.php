<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach ($items as $key => $breadcrumb)
            @if (isset($breadcrumb['url']) && $key < count($items) - 1)
                <li class="breadcrumb-item">
                    <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none"
                        wire:navigate>{{ $breadcrumb['name'] }}</a>
                </li>
            @else
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $breadcrumb['name'] }}
                </li>
            @endif
        @endforeach
    </ol>
</nav>
