import * as bootstrap from "bootstrap";
import Swal from "sweetalert2";

// Setup Toast sekali saja
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
});

// sweetalert modal
const Swal2 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-danger me-2",
        cancelButton: "btn btn-light",
    },
    buttonsStyling: false,
});

window.bootstrap = bootstrap;
document.addEventListener("livewire:init", () => {
    // sweetalert toast
    Livewire.on("success-add-data", (data) => {
        Toast.fire({
            icon: "success",
            title: data.message || "berhasil tambah data",
        });
    });
    Livewire.on("success-delete-data", (data) => {
        Toast.fire({
            icon: "success",
            title: data.message || "berhasil menghapus data",
        });
    });
    Livewire.on("success-edit-data", (data) => {
        Toast.fire({
            icon: "success",
            title: data.message || "berhasil mengubah data",
        });
    });
    Livewire.on("failed-add-data", (data) => {
        Toast.fire({
            icon: "error",
            title: data.message || "gagal menambah data",
        });
    });
    Livewire.on("failed-edit-data", (data) => {
        Toast.fire({
            icon: "error",
            title: data.message || "gagal mengubah data",
        });
    });
    Livewire.on("failed-delete-data", (data) => {
        Toast.fire({
            icon: "error",
            title: data.message || "gagal menghapus data",
        });
    });

    // sweetalert modal
    Livewire.on("confirm-delete-data-bidang", (data) => {
        Swal2.fire({
            icon: "question",
            title:
                "Yakin ingin hapus data bidang <strong class='text-primary'>" +
                data["nama_bidang"] +
                "</strong> ?",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonText: "Ya, hapus",
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch("delete-data-bidang", { id: data["id"] });
            }
        });
    });

    Livewire.on("confirm-delete-data-kegiatan", (data) => {
        Swal2.fire({
            icon: "question",
            title:
                "Yakin ingin hapus data kegiatan <strong class='text-primary'>" +
                data["nama_kegiatan"] +
                "</strong> ?",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonText: "Ya, hapus",
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch("delete-data-kegiatan", { id: data["id"] });
            }
        });
    });

    Livewire.on("confirm-delete-data-jabatan", (data) => {
        Swal2.fire({
            icon: "question",
            title:
                "Yakin ingin hapus data jabatan <strong class='text-primary'>" +
                data["nama_jabatan"] +
                "</strong> ?",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonText: "Ya, hapus",
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch("delete-data-jabatan", { id: data["id"] });
            }
        });
    });
});
