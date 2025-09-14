@props([
'triggerClass' => '',
'placement' => 'bottom', // bottom|top
])

<div x-data="popoverComponent('{{ $placement }}')" class="inline-block">
    <!-- Trigger -->
    <div x-ref="trigger" @click="toggle" class="{{ $triggerClass }}" style="cursor: pointer">
        {{ $trigger }}
    </div>

    <!-- Popover element (rendered in place but akan dipindah ke body pada init) -->
    <div x-ref="popover" x-cloak x-show="open" @click.away="close" class="rounded-1 shadow-lg bg-white p-3"
        style="min-width:180px; display:none;">
        {{ $slot }}
    </div>
</div>

{{-- <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('popoverComponent', (defaultPlacement) => ({
            open: false,
            placement: defaultPlacement || 'bottom',
            triggerEl: null,
            popoverEl: null,
            margin: 20, // minimal jarak ke tepi viewport
            resizeHandler: null,
            scrollHandler: null,

            init() {
                // nothing here: we will reference $refs when first toggle to be safe
                // but attach handlers after we got popoverEl
            },

            toggle() {
                // ensure refs available
                this.triggerEl = this.$refs.trigger;
                this.popoverEl = this.$refs.popover;

                if (!this.popoverEl || !this.triggerEl) return;

                // move popover to body if not already moved, but ensure popoverEl is a Node
                if (this.popoverEl && this.popoverEl.nodeType === Node.ELEMENT_NODE) {
                    if (this.popoverEl.parentElement !== document.body) {
                        document.body.appendChild(this.popoverEl);
                        this.popoverEl.style.position = 'fixed';
                        this.popoverEl.style.zIndex = 9999;
                        // ensure Alpine still controls display via x-show; we'll use measurement tricks below
                    }
                } else {
                    console.warn('Popover element not ready to append.');
                }

                this.open = !this.open;

                if (this.open) {
                    // position after DOM update / Alpine x-show applied
                    this.$nextTick(() => {
                        this.positionPopover();
                        // attach handlers once
                        if (!this.resizeHandler) {
                            this.resizeHandler = () => {
                                if (this.open) this.positionPopover();
                            };
                            this.scrollHandler = () => {
                                if (this.open) this.positionPopover();
                            };
                            window.addEventListener('resize', this.resizeHandler);
                            window.addEventListener('scroll', this.scrollHandler, true);
                        }
                    });
                } else {
                    // closed -> hide (Alpine x-show will hide element), but keep element appended
                }
            },

            close() {
                this.open = false;
            },

            positionPopover() {
                if (!this.popoverEl || !this.triggerEl) return;

                // Temporarily force show (hidden visibility) to measure size reliably
                const prevDisplay = this.popoverEl.style.display;
                const prevVisibility = this.popoverEl.style.visibility;

                // Make visible offscreen for measurement
                this.popoverEl.style.visibility = 'hidden';
                this.popoverEl.style.display = 'block';

                const triggerRect = this.triggerEl.getBoundingClientRect();
                const popRect = this.popoverEl.getBoundingClientRect();
                const vw = window.innerWidth;
                const vh = window.innerHeight;

                // decide vertical placement (auto flip if not enough space)
                let placement = this.placement;
                const spaceBelow = vh - triggerRect.bottom;
                const spaceAbove = triggerRect.top;

                if (placement === 'bottom' && spaceBelow < popRect.height + this.margin) {
                    placement = 'top';
                } else if (placement === 'top' && spaceAbove < popRect.height + this.margin) {
                    placement = 'bottom';
                }

                // compute top (fixed coordinates: client rect already relative to viewport)
                let top;
                const offset = 8; // gap between trigger and popover
                if (placement === 'bottom') {
                    top = triggerRect.bottom + offset;
                } else {
                    top = triggerRect.top - popRect.height - offset;
                }

                // compute horizontal: prefer centered under trigger,
                // but if would overflow right/left, adjust and if needed align to trigger edge
                let left = triggerRect.left + (triggerRect.width / 2) - (popRect.width / 2);

                // if popover still goes beyond left boundary
                if (left < this.margin) {
                    left = this.margin;
                }

                // if popover goes beyond right boundary
                if (left + popRect.width > vw - this.margin) {
                    // try align right edge to trigger right
                    const altLeft = triggerRect.right - popRect.width;
                    if (altLeft >= this.margin) {
                        left = altLeft;
                    } else {
                        // fallback: push to max allowed
                        left = Math.max(this.margin, vw - popRect.width - this.margin);
                    }
                }

                // set final position using fixed coords (no scroll offset)
                this.popoverEl.style.left = `${Math.round(left)}px`;
                this.popoverEl.style.top = `${Math.round(top)}px`;

                // restore visibility & ensure shown if open
                this.popoverEl.style.visibility = prevVisibility || '';
                this.popoverEl.style.display = this.open ? 'block' : 'none';
            }
        }));
    });
</script> --}}

{{-- <script>
    // Fungsi untuk memastikan Alpine ready
    function initPopoverComponent() {
        if (typeof Alpine === 'undefined') {
            setTimeout(initPopoverComponent, 50);
            return;
        }

        // Check jika sudah terdefinisi untuk menghindari duplikasi
        if (Alpine.store && Alpine.store('popoverRegistered')) {
            return;
        }

        Alpine.data('popoverComponent', (defaultPlacement) => ({
            open: false,
            placement: defaultPlacement || 'bottom',
            triggerEl: null,
            popoverEl: null,
            margin: 20, // minimal jarak ke tepi viewport
            resizeHandler: null,
            scrollHandler: null,
            cleanupHandlers: null,

            init() {
                // Setup cleanup function untuk event listeners
                this.cleanupHandlers = () => {
                    if (this.resizeHandler) {
                        window.removeEventListener('resize', this.resizeHandler);
                    }
                    if (this.scrollHandler) {
                        window.removeEventListener('scroll', this.scrollHandler, true);
                    }
                };

                // Cleanup saat component destroy
                this.$cleanup(this.cleanupHandlers);
            },

            toggle() {
                // Pastikan refs tersedia
                this.triggerEl = this.$refs.trigger;
                this.popoverEl = this.$refs.popover;

                if (!this.popoverEl || !this.triggerEl) {
                    console.warn('Trigger atau popover element tidak ditemukan');
                    return;
                }

                // Pindahkan popover ke body jika belum dipindahkan
                if (this.popoverEl && this.popoverEl.nodeType === Node.ELEMENT_NODE) {
                    if (this.popoverEl.parentElement !== document.body) {
                        // Clone styling classes untuk mempertahankan styling
                        const computedStyle = window.getComputedStyle(this.popoverEl);

                        document.body.appendChild(this.popoverEl);
                        this.popoverEl.style.position = 'fixed';
                        this.popoverEl.style.zIndex = '9999';

                        // Pastikan background dan border styling tetap ada jika hilang
                        if (!this.popoverEl.style.backgroundColor && !computedStyle.backgroundColor.includes('rgba(0, 0, 0, 0)')) {
                            // Background sudah ada dari class
                        }
                    }
                } else {
                    console.warn('Popover element tidak siap untuk dipindahkan.');
                    return;
                }

                this.open = !this.open;

                if (this.open) {
                    // Position setelah DOM update menggunakan requestAnimationFrame
                    // yang lebih reliable daripada $nextTick untuk positioning
                    requestAnimationFrame(() => {
                        this.positionPopover();
                        this.attachEventHandlers();
                    });
                }
            },

            close() {
                this.open = false;
            },

            attachEventHandlers() {
                // Attach handlers hanya sekali
                if (!this.resizeHandler) {
                    this.resizeHandler = this.debounce(() => {
                        if (this.open) this.positionPopover();
                    }, 100);

                    this.scrollHandler = this.debounce(() => {
                        if (this.open) this.positionPopover();
                    }, 50);

                    window.addEventListener('resize', this.resizeHandler);
                    window.addEventListener('scroll', this.scrollHandler, { passive: true, capture: true });
                }
            },

            // Debounce utility untuk performance
            debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func.apply(this, args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            },

            positionPopover() {
                if (!this.popoverEl || !this.triggerEl) return;

                // Gunakan intersection observer API untuk deteksi visibility yang lebih baik
                const triggerRect = this.triggerEl.getBoundingClientRect();

                // Check jika trigger masih visible di viewport
                if (triggerRect.bottom < 0 || triggerRect.top > window.innerHeight ||
                    triggerRect.right < 0 || triggerRect.left > window.innerWidth) {
                    this.close(); // Auto close jika trigger tidak terlihat
                    return;
                }

                // Temporarily show untuk measurement dengan cara yang lebih robust
                const originalStyles = {
                    display: this.popoverEl.style.display,
                    visibility: this.popoverEl.style.visibility,
                    position: this.popoverEl.style.position,
                    left: this.popoverEl.style.left,
                    top: this.popoverEl.style.top
                };

                // Set untuk measurement
                Object.assign(this.popoverEl.style, {
                    visibility: 'hidden',
                    display: 'block',
                    position: 'fixed',
                    left: '-9999px',
                    top: '-9999px'
                });

                const popRect = this.popoverEl.getBoundingClientRect();
                const vw = window.innerWidth;
                const vh = window.innerHeight;

                // Smart placement dengan auto-flip
                let placement = this.placement;
                const spaceBelow = vh - triggerRect.bottom;
                const spaceAbove = triggerRect.top;

                if (placement === 'bottom' && spaceBelow < popRect.height + this.margin + 10) {
                    if (spaceAbove > spaceBelow) {
                        placement = 'top';
                    }
                } else if (placement === 'top' && spaceAbove < popRect.height + this.margin + 10) {
                    if (spaceBelow > spaceAbove) {
                        placement = 'bottom';
                    }
                }

                // Compute vertical position
                let top;
                const offset = 8;
                if (placement === 'bottom') {
                    top = triggerRect.bottom + offset;
                } else {
                    top = triggerRect.top - popRect.height - offset;
                }

                // Ensure popover stays within vertical bounds
                top = Math.max(this.margin, Math.min(top, vh - popRect.height - this.margin));

                // Compute horizontal position (centered, tapi adjust jika overflow)
                let left = triggerRect.left + (triggerRect.width / 2) - (popRect.width / 2);

                // Adjust untuk horizontal bounds
                if (left < this.margin) {
                    left = this.margin;
                } else if (left + popRect.width > vw - this.margin) {
                    left = vw - popRect.width - this.margin;
                }

                // Set final position
                Object.assign(this.popoverEl.style, {
                    left: `${Math.round(left)}px`,
                    top: `${Math.round(top)}px`,
                    visibility: 'visible',
                    display: 'block',
                    position: 'fixed'
                });
            }
        }));

        // Mark sebagai registered untuk avoid duplikasi
        if (Alpine.store) {
            Alpine.store('popoverRegistered', true);
        }
    }

    // Multiple initialization strategies untuk reliability
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPopoverComponent);
    } else {
        initPopoverComponent();
    }

    // Alpine.js event listeners
    document.addEventListener('alpine:init', initPopoverComponent);

    // Livewire event listeners (jika menggunakan Livewire)
    document.addEventListener('livewire:navigated', initPopoverComponent);
    document.addEventListener('livewire:init', initPopoverComponent);
</script> --}}

<script>
    // Fungsi untuk memastikan Alpine ready
    function initPopoverComponent() {
        if (typeof Alpine === 'undefined') {
            setTimeout(initPopoverComponent, 50);
            return;
        }

        // Check jika sudah terdefinisi untuk menghindari duplikasi
        if (Alpine.store && Alpine.store('popoverRegistered')) {
            return;
        }

        Alpine.data('popoverComponent', (defaultPlacement) => ({
            open: false,
            placement: defaultPlacement || 'bottom',
            triggerEl: null,
            popoverEl: null,
            margin: 20, // minimal jarak ke tepi viewport
            resizeHandler: null,
            scrollHandler: null,

            init() {
                // Tidak ada cleanup otomatis di init
                // Event handlers akan di-manage di attachEventHandlers dan removeEventHandlers
            },

            toggle() {
                // Pastikan refs tersedia
                this.triggerEl = this.$refs.trigger;
                this.popoverEl = this.$refs.popover;

                if (!this.popoverEl || !this.triggerEl) {
                    console.warn('Trigger atau popover element tidak ditemukan');
                    return;
                }

                // Pindahkan popover ke body jika belum dipindahkan
                if (this.popoverEl && this.popoverEl.nodeType === Node.ELEMENT_NODE) {
                    if (this.popoverEl.parentElement !== document.body) {
                        // Clone styling classes untuk mempertahankan styling
                        const computedStyle = window.getComputedStyle(this.popoverEl);

                        document.body.appendChild(this.popoverEl);
                        this.popoverEl.style.position = 'fixed';
                        this.popoverEl.style.zIndex = '9999';

                        // Pastikan background dan border styling tetap ada jika hilang
                        if (!this.popoverEl.style.backgroundColor && !computedStyle.backgroundColor.includes('rgba(0, 0, 0, 0)')) {
                            // Background sudah ada dari class
                        }
                    }
                } else {
                    console.warn('Popover element tidak siap untuk dipindahkan.');
                    return;
                }

                this.open = !this.open;

                if (this.open) {
                    // Position setelah DOM update menggunakan requestAnimationFrame
                    // yang lebih reliable daripada $nextTick untuk positioning
                    requestAnimationFrame(() => {
                        this.positionPopover();
                        this.attachEventHandlers();
                    });
                }
            },

            close() {
                this.open = false;
                // Optional: remove event handlers saat close untuk menghemat memory
                // Uncomment jika ingin cleanup setiap close
                // this.removeEventHandlers();
            },

            destroy() {
                // Manual cleanup method yang bisa dipanggil
                this.removeEventHandlers();
            },

            attachEventHandlers() {
                // Clean up existing handlers first
                this.removeEventHandlers();

                // Attach handlers hanya sekali
                this.resizeHandler = this.debounce(() => {
                    if (this.open) this.positionPopover();
                }, 100);

                this.scrollHandler = this.debounce(() => {
                    if (this.open) this.positionPopover();
                }, 50);

                window.addEventListener('resize', this.resizeHandler);
                window.addEventListener('scroll', this.scrollHandler, { passive: true, capture: true });
            },

            removeEventHandlers() {
                if (this.resizeHandler) {
                    window.removeEventListener('resize', this.resizeHandler);
                    this.resizeHandler = null;
                }
                if (this.scrollHandler) {
                    window.removeEventListener('scroll', this.scrollHandler, true);
                    this.scrollHandler = null;
                }
            },

            // Debounce utility untuk performance
            debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func.apply(this, args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            },

            positionPopover() {
                if (!this.popoverEl || !this.triggerEl) return;

                // Gunakan intersection observer API untuk deteksi visibility yang lebih baik
                const triggerRect = this.triggerEl.getBoundingClientRect();

                // Check jika trigger masih visible di viewport
                if (triggerRect.bottom < 0 || triggerRect.top > window.innerHeight ||
                    triggerRect.right < 0 || triggerRect.left > window.innerWidth) {
                    this.close(); // Auto close jika trigger tidak terlihat
                    return;
                }

                // Temporarily show untuk measurement dengan cara yang lebih robust
                const originalStyles = {
                    display: this.popoverEl.style.display,
                    visibility: this.popoverEl.style.visibility,
                    position: this.popoverEl.style.position,
                    left: this.popoverEl.style.left,
                    top: this.popoverEl.style.top
                };

                // Set untuk measurement
                Object.assign(this.popoverEl.style, {
                    visibility: 'hidden',
                    display: 'block',
                    position: 'fixed',
                    left: '-9999px',
                    top: '-9999px'
                });

                const popRect = this.popoverEl.getBoundingClientRect();
                const vw = window.innerWidth;
                const vh = window.innerHeight;

                // Smart placement dengan auto-flip
                let placement = this.placement;
                const spaceBelow = vh - triggerRect.bottom;
                const spaceAbove = triggerRect.top;

                if (placement === 'bottom' && spaceBelow < popRect.height + this.margin + 10) {
                    if (spaceAbove > spaceBelow) {
                        placement = 'top';
                    }
                } else if (placement === 'top' && spaceAbove < popRect.height + this.margin + 10) {
                    if (spaceBelow > spaceAbove) {
                        placement = 'bottom';
                    }
                }

                // Compute vertical position
                let top;
                const offset = 8;
                if (placement === 'bottom') {
                    top = triggerRect.bottom + offset;
                } else {
                    top = triggerRect.top - popRect.height - offset;
                }

                // Ensure popover stays within vertical bounds
                top = Math.max(this.margin, Math.min(top, vh - popRect.height - this.margin));

                // Compute horizontal position (centered, tapi adjust jika overflow)
                let left = triggerRect.left + (triggerRect.width / 2) - (popRect.width / 2);

                // Adjust untuk horizontal bounds
                if (left < this.margin) {
                    left = this.margin;
                } else if (left + popRect.width > vw - this.margin) {
                    left = vw - popRect.width - this.margin;
                }

                // Set final position
                Object.assign(this.popoverEl.style, {
                    left: `${Math.round(left)}px`,
                    top: `${Math.round(top)}px`,
                    visibility: 'visible',
                    display: 'block',
                    position: 'fixed'
                });
            }
        }));

        // Mark sebagai registered untuk avoid duplikasi
        if (Alpine.store) {
            Alpine.store('popoverRegistered', true);
        }
    }

    // Multiple initialization strategies untuk reliability
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPopoverComponent);
    } else {
        initPopoverComponent();
    }

    // Alpine.js event listeners
    document.addEventListener('alpine:init', initPopoverComponent);

    // Livewire event listeners (jika menggunakan Livewire)
    document.addEventListener('livewire:navigated', initPopoverComponent);
    document.addEventListener('livewire:init', initPopoverComponent);
</script>