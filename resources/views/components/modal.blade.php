@props(['title' => '', 'size' => 'modal-lg', 'closable' => true, 'show' => false, 'closeHandler' => null])

<div x-data="{ show: @js($show) }" x-show="show" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
    style="background-color: rgba(0, 0, 0, 0.5); z-index: 1050; display: none; z-index: 1060;" {{ $attributes }}>

    <div class="{{ $size }}" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-75" x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-75" style="max-width: 500px; width: 90%;">

        <div class="modal-content bg-white rounded shadow p-4">
            <!-- Modal Header -->
            <div class="modal-header mb-3">
                <h5 class="modal-title fw-bold">{{ $title }}</h5>
                @if ($closable)
                    {{ $closeButton }}
                @endif
            </div>

            <!-- Modal Body -->
            <div class="modal-body mb-3">
                {{ $slot }}
            </div>

            <!-- Modal Footer -->
            @isset($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
