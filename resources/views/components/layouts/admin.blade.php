@include('partials.admin.layout.head')

<body x-cloak x-data="{ sidebarCollapsed: $persist(false), showSidebar: false }">

    <!-- Sidebar -->
    @include('partials.admin.layout.sidebar')

    <!-- Main Content Area -->
    <div class="main-content" :class="{ 'expanded': sidebarCollapsed }">
        <!-- Header -->
        @include('partials.admin.layout.header')

        <!-- Main -->
        <main class="px-4 py-3 bg-white rounded-1 shadow-sm">
            {{ $slot }}
        </main>
    </div>
    {{-- Scripts --}}
    <script>
        // lightbox.option({
        //     'resizeDuration': 200,
        //     'wrapAround': true,
        //     'showImageNumberLabel': true,
        //     'albumLabel': "Foto %1 dari %2"
        // });
    </script>
    @stack('scripts') {{-- Tambahkan stack untuk JS khusus --}}
    @livewireScripts
</body>

</html>
