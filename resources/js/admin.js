import * as bootstrap from "bootstrap";
import { Popover } from "bootstrap";

window.bootstrap = bootstrap;

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach((el) => {
        new Popover(el, {
            html: true,
            container: ".layout",
        });
    });

    Livewire.on("close-modal", () => {
        console.log("event modal di tutup di terima");
        const el = document.getElementById("deleteModal");
        if (!el) return;
        const modal =
            bootstrap.Modal.getInstance(el) ??
            bootstrap.Modal.getOrCreateInstance(el);
        modal.hide();
    });
});
