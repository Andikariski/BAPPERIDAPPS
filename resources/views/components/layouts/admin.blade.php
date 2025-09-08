@include('partials.admin.layout.head')

<body x-cloak x-data="sidebarApp()" x-init="init()" class="bg-light" data-bs-theme="light">
    <div class="layout">
        <!-- Sidebar -->
        @include('partials.admin.layout.sidebar')

        <!-- Main Content Area -->
        <div class="content d-flex flex-column gap-2" :style="sidebarOpen ? 'margin-left:0' : 'margin-left:0'">
            <!-- Header -->
            @include('partials.admin.layout.header')

            <!-- Main -->
            <main class="px-4 py-3 bg-white rounded-1 shadow-sm">
                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        function sidebarApp() {
            return {
                sidebarOpen: true,
                theme: localStorage.getItem("theme") || "light",
                collapsed: JSON.parse(
                    localStorage.getItem("sidebarCollapse") || '{"orders":false}'
                ),
                init() {
                    document.documentElement.setAttribute("data-bs-theme", this.theme);
                },
                toggleTheme() {
                    this.theme = this.theme === "light" ? "dark" : "light";
                    document.documentElement.setAttribute("data-bs-theme", this.theme);
                    localStorage.setItem("theme", this.theme);
                },
                saveState(menu, value) {
                    this.collapsed[menu] = value;
                    localStorage.setItem(
                        "sidebarCollapse",
                        JSON.stringify(this.collapsed)
                    );
                },
            };
        }
    </script>
    @stack('scripts') {{-- Tambahkan stack untuk JS khusus --}}
    @livewireScripts
</body>

</html>
