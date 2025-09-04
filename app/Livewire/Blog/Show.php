<?php

namespace App\Livewire\Blog;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public $post;

    public function mount($slug)
    {
        $this->post = Post::with('category', 'tags', 'author')
            ->where('slug', $slug)
            ->firstOrFail();
    }

    #[Layout('components.layouts.public')]
    public function render()
    {
        return view('livewire.blog.show');
    }
}
