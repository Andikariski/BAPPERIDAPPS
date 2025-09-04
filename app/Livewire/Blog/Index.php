<?php

namespace App\Livewire\Blog;

use App\Models\Category;
use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';

    protected $queryString = ['search', 'category', 'page'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }


    #[Layout('components.layouts.public')]
    public function render()
    {
        $query = Post::with('category', 'tags', 'author')
            ->where('status', 'published');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('content', 'like', "%{$this->search}%");
            });
        }

        if ($this->category) {
            $query->whereHas('category', function ($q) {
                $q->where('slug', $this->category);
            });
        }

        return view('livewire.blog.index', [
            'posts' => $query->latest()->paginate(6),
            'categories' => Category::all(),
        ]);
    }
}
