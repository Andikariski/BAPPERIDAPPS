<?php

namespace App\Livewire\Admin\Berita;

use App\Models\Berita as ModelsBerita;
use DOMDocument;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Berita extends Component
{
    use WithPagination;

    public $searchBerita = '';
    public $filterStatus = 'active';

    protected $queryString = [
        'searchBerita' => ['except' => ''],
        'filterStatus' => ['except' => 'active']
    ];

    // Method untuk reset filter
    public function resetFilters()
    {
        $this->reset(['searchBerita', 'filterStatus']);
    }

    // Method untuk quick filter
    public function setFilter($status)
    {
        $this->filterStatus = $status;
        $this->resetPage();
    }

    #[On('soft-delete-data-berita')]
    public function delete($id)
    {
        try {
            $berita = ModelsBerita::findOrFail($id);

            $berita->delete();
            $this->dispatch('success-archive-data');
        } catch (Exception $e) {
            $this->dispatch('failed-delete-data');
        }
    }

    #[On('force-delete-data-berita')]
    public function forceDelete($id)
    {
        try {
            // Gunakan withTrashed() untuk mengambil data yang sudah di-soft delete
            $berita = ModelsBerita::withTrashed()->findOrFail($id);

            // Delete thumbnail image
            if ($berita->foto_thumbnail) {
                $filePath = 'foto_thumbnail_berita/' . $berita->foto_thumbnail;
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            // Delete images from konten berita
            if ($berita->konten_berita) {
                $dom = new DOMDocument();
                libxml_use_internal_errors(true); // Suppress HTML parsing warnings
                $dom->loadHTML($berita->konten_berita, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

                $images = $dom->getElementsByTagName('img');
                foreach ($images as $img) {
                    $src = $img->getAttribute('src');
                    if (strpos($src, 'foto-foto-berita') !== false) {
                        $filename = basename(parse_url($src, PHP_URL_PATH));
                        $imagePath = 'foto-foto-berita/' . $filename;

                        if (Storage::disk('public')->exists($imagePath)) {
                            Storage::disk('public')->delete($imagePath);
                        }
                    }
                }
            }

            // Force delete (hapus permanen dari database)
            $berita->forceDelete();

            $this->dispatch('success-delete-data');
        } catch (Exception $e) {
            $this->dispatch('failed-delete-data');
        }
    }

    // Method untuk restore data yang di-soft delete
    #[On('restore-data-berita')]
    public function restore($id)
    {
        try {
            $berita = ModelsBerita::withTrashed()->findOrFail($id);
            $berita->restore();

            $this->dispatch('success-restore-data');
        } catch (Exception $e) {
            $this->dispatch('failed-edit-data');
        }
    }

    #[Layout('components.layouts.admin', ['title' => 'Admin | Berita', 'pageTitle' => 'Berita'])]
    public function render()
    {
        $query = ModelsBerita::query()->with('bidang');

        switch ($this->filterStatus) {
            case 'trashed':
                $query->onlyTrashed();
                break;
            case 'all':
                $query->withTrashed();
                break;
            case 'active':
            default:
                break;
        }

        // Apply search filter
        if ($this->searchBerita) {
            $query->where('judul_berita', 'like', "%{$this->searchBerita}%");
        }

        $dataBerita = $query->latest()->paginate(10);

        return view('livewire.admin.berita.berita', [
            'dataBerita' => $dataBerita
        ]);
    }
}
