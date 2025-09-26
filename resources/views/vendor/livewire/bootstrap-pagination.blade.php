@if ($paginator->hasPages())
    <nav class="d-flex justify-content-between align-items-center">
        {{-- Info Artikel --}}
        <div class="small text-muted">
            Menampilkan
            <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
            sampai
            <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
            dari
            <span class="fw-semibold">{{ $paginator->total() }}</span>
            data
        </div>

        {{-- Link Pagination --}}
        <ul class="pagination mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">@lang('pagination.previous')</span>
                </li>
            @else
                <li class="page-item">
                    <button type="button" class="page-link" wire:click="previousPage" wire:loading.attr="disabled"
                        rel="prev">
                        @lang('pagination.previous')
                    </button>
                </li>
            @endif

            {{-- Pagination Elements --}}
            {{-- Pagination Elements --}}
            @php
                $totalPages = $paginator->lastPage();
                $currentPage = $paginator->currentPage();
                $start = max($currentPage - 2, 1); // Mulai 2 halaman sebelum current
                $end = min($currentPage + 2, $totalPages); // Sampai 2 halaman setelah current
            @endphp

            {{-- First Page --}}
            @if ($start > 1)
                <li class="page-item">
                    <button type="button" class="page-link" wire:click="gotoPage(1)">1</button>
                </li>
                @if ($start > 2)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
            @endif

            {{-- Page Range --}}
            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $currentPage)
                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                @else
                    <li class="page-item">
                        <button type="button" class="page-link"
                            wire:click="gotoPage({{ $i }})">{{ $i }}</button>
                    </li>
                @endif
            @endfor

            {{-- Last Page --}}
            @if ($end < $totalPages)
                @if ($end < $totalPages - 1)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                <li class="page-item">
                    <button type="button" class="page-link"
                        wire:click="gotoPage({{ $totalPages }})">{{ $totalPages }}</button>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <button type="button" class="page-link" wire:click="nextPage" wire:loading.attr="disabled"
                        rel="next">
                        @lang('pagination.next')
                    </button>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">@lang('pagination.next')</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
