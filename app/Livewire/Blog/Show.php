<?php

namespace App\Livewire\Blog;

use App\Models\Berita;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public $berita;

    public function mount($slug)
    {

        $this->berita = Berita::with('bidang', 'tags', 'author')
            ->where('slug', $slug)
            ->firstOrFail();
    }

    #[Layout('components.layouts.public')]
    public function render()
    {
        return view('livewire.blog.show');
    }
}
