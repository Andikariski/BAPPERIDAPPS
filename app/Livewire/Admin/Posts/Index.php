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
    public $selectedPostId = null;
    public $selectedPostTitle = '';
    public $showDeleteModal = false;

    public function confirmDelete($id)
    {
        $post = Post::find($id);
        if ($post) {
            $this->selectedPostId = $id;
            $this->selectedPostTitle = $post->title;
            $this->showDeleteModal   = true;
        }
    }

    public function delete()
    {
        Post::find($this->selectedPostId)?->delete();

        $this->reset(['selectedPostId', 'selectedPostTitle']);
        $this->dispatch('close-modal');
    }

    #[Layout('components.layouts.admin', ['title' => 'Admin | Berita', 'pageTitle' => 'Berita'])]
    public function render()
    {
        $posts = Post::query()
            ->with('category')
            ->where('title', 'like', "%{$this->search}%")
            ->latest()
            ->paginate(20);
        return view('livewire.admin.posts.index', [
            'posts' => $posts
        ]);
    }
}
