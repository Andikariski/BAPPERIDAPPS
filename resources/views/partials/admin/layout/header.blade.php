<header class="header d-flex justify-content-between align-items-center ms-2">
    <div class="d-flex align-items-center">
        <!-- Desktop Sidebar Toggle -->
        <button class="toggle-btn me-3 d-none d-md-block" @click="sidebarCollapsed = !sidebarCollapsed"
            title="Toggle Sidebar">
            <i class="bi bi-list"></i>
        </button>

        <!-- Mobile Menu Toggle -->
        <button class="toggle-btn me-3 d-md-none" @click="showSidebar = !showSidebar" title="Toggle Mobile Menu">
            <i class="bi bi-list"></i>
        </button>

        <div>
            <h4 class="mb-0">{{ $pageTitle ?? 'Dashboard' }}</h4>
        </div>
    </div>
    

    <div class="d-flex align-items-center">
        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <button class="btn bg-light dropdown-toggle text-dark" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-2"></i>{{ auth()->user()->name ?? 'Admin' }}
            </button>
            
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <button wire:click="$dispatch('coming-soon')" class="dropdown-item btn" id="testAlertBtn1">
                        <i class="bi bi-person me-2"></i>Profile
                    </button>
                </li>
                <li>
                    <button wire:click="$dispatch('coming-soon')" class="dropdown-item btn" id="testAlertBtn2">
                        <i class="bi bi-gear me-2"></i>Settings
                    </button>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js','resources/js/admin.js']) --}}
</header>

<!-- Tambahkan di <head> atau sebelum </body> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    const testButtonProfile = document.getElementById("testAlertBtn1");
    const testButtonSetting = document.getElementById("testAlertBtn2");

    if (testButtonProfile) {
        testButtonProfile.addEventListener("click", function() {
            Swal.fire({
                icon: "warning",
                title: "Fitur Akan Segera Hadir",
                showCancelButton: false,
                confirmButtonText: "Tutup Notifikasi",
                footer: '<strong class="text-danger">Fitur masih dalam tahap pengembangan!</strong>',
            });
        });
    }
    if(testButtonSetting){
        testButtonSetting.addEventListener("click", function() {
            Swal.fire({
                icon: "warning",
                title: "Fitur Akan Segera Hadir",
                showCancelButton: false,
                confirmButtonText: "Tutup Notifikasi",
                footer: '<strong class="text-danger">Fitur masih dalam tahap pengembangan!</strong>',
            });
        });
    }
});

</script>