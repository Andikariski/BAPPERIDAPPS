import "bootstrap";
// ... other imports
import { Popover } from "bootstrap";

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach((el) => {
        new Popover(el, {
            html: true,
            container: ".layout",
        });
    });
});
