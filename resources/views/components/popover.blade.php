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

<script>
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
</script>
