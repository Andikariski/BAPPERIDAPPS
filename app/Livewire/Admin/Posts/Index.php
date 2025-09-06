<?php

namespace App\Livewire\Admin\Posts;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete(Post $post)
    {
        $post->delete();
        session()->flash('message', 'Post deleted successfully.');
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        $posts = Post::query()
            ->with('category')
            ->where('title', 'like', "%{$this->search}%")
            ->latest()
            ->paginate(10);

        return view('livewire.admin.posts.index', [
            'posts' => $posts,
        ]);
    }
}
