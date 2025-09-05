<nav class="flex mb-4" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        @foreach ($items as $key => $breadcrumb)
            <li>
                <div class="flex items-center">
                    @if ($key > 0)
                        <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    @endif

                    @if (isset($breadcrumb['url']))
                        <a href="{{ $breadcrumb['url'] }}"
                            class="ml-1 text-gray-700 hover:text-blue-600 text-sm font-medium">
                            {{ $breadcrumb['name'] }}
                        </a>
                    @else
                        <span class="ml-1 text-gray-500 text-sm font-medium">{{ $breadcrumb['name'] }}</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
