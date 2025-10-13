<?php

namespace App\Livewire\Blog;

use App\Models\Berita;
use App\Models\Bidang;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedBidang = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedBidang()
    {
        $this->resetPage();
    }


    #[Layout('components.layouts.public')]
    public function render()
    {
        $query = Berita::with('bidang', 'tags', 'author')
            ->where('status_publikasi', 'published');

        if (!empty($this->search)) {
            $query->where('judul_berita', 'like', "%{$this->search}%");
        }
        if (!empty($this->selectedBidang)) {
            $query->where('fkid_bidang', $this->selectedBidang);
        }

        return view('livewire.blog.index', [
            'posts' => $query->latest()->paginate(6),
            'dataBidang' => Bidang::all(),
        ]);
    }
}
