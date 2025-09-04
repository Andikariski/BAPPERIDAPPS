@include('partials.homepage.head')

<body class="index-page">
    {{-- Header --}}
    @include('partials.homepage.header')

    {{-- Main Content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    @include('partials.homepage.footer')

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <div id="preloader"></div>

    {{-- Scripts --}}
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    @stack('scripts') {{-- Tambahkan stack untuk JS khusus --}}
    @livewireScripts
</body>

</html>
