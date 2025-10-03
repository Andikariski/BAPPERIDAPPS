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
    public $bidang = '';

    protected $queryString = ['search', 'bidang', 'page'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedBidang()
    {
        $this->resetPage();
    }


    #[Layout('components.layouts.public')]
    public function render()
    {
        $query = Berita::with('bidang', 'tags', 'author')
            ->where('status_publikasi', 'published');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('judul_berita', 'like', "%{$this->search}%")
                    ->orWhere('konten_berita', 'like', "%{$this->search}%");
            });
        }

        if ($this->bidang) {
            $query->whereHas('bidang', function ($q) {
                $q->where('id', $this->bidang);
            });
        }

        return view('livewire.blog.index', [
            'posts' => $query->latest()->paginate(6),
            'dataBidang' => Bidang::all(),
        ]);
    }
}
