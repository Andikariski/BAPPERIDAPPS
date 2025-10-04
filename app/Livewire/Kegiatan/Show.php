<?php

namespace App\Livewire\Kegiatan;

use App\Models\Kegiatan;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public $kegiatan;
    public $showModal = false;
    public $selectedPhoto = null;

    public function mount($id)
    {
        $this->kegiatan = Kegiatan::with('bidang')
            ->where('id', $id)
            ->firstOrFail();
    }

    public function openModal($photoId)
    {
        $this->selectedPhoto = $this->kegiatan->fotoKegiatan->find($photoId);
        $this->showModal = true;
        $this->dispatch('open-photo-modal');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedPhoto = null;
        $this->dispatch('close-photo-modal');
    }

    public function nextPhoto()
    {
        if (!$this->selectedPhoto) return;

        $currentUrutan = $this->selectedPhoto->urutan;

        // Ambil foto dengan urutan berikutnya
        $nextPhoto = $this->kegiatan->fotoKegiatan
            ->where('urutan', '>', $currentUrutan)
            ->sortBy('urutan')
            ->first();

        // Jika tidak ada, ambil foto pertama (loop ke awal)
        if (!$nextPhoto) {
            $nextPhoto = $this->kegiatan->fotoKegiatan
                ->sortBy('urutan')
                ->first();
        }

        $this->selectedPhoto = $nextPhoto;
    }

    public function previousPhoto()
    {
        if (!$this->selectedPhoto) return;

        $currentUrutan = $this->selectedPhoto->urutan;

        // Ambil foto dengan urutan sebelumnya
        $prevPhoto = $this->kegiatan->fotoKegiatan
            ->where('urutan', '<', $currentUrutan)
            ->sortByDesc('urutan')
            ->first();

        // Jika tidak ada, ambil foto terakhir (loop ke akhir)
        if (!$prevPhoto) {
            $prevPhoto = $this->kegiatan->fotoKegiatan
                ->sortByDesc('urutan')
                ->first();
        }

        $this->selectedPhoto = $prevPhoto;
    }

    public function getCurrentPhotoPosition()
    {
        if (!$this->selectedPhoto) return [0, 0];

        $sortedPhotos = $this->kegiatan->fotoKegiatan->sortBy('urutan')->values();
        $currentIndex = $sortedPhotos->search(function ($photo) {
            return $photo->id === $this->selectedPhoto->id;
        });

        return [
            'current' => $currentIndex + 1,
            'total' => $sortedPhotos->count()
        ];
    }

    #[Layout('components.layouts.public')]
    public function render()
    {
        return view('livewire.kegiatan.show');
    }
}
