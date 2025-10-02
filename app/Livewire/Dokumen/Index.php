<?php

namespace App\Livewire\Dokumen;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Index extends Component
{
    #[Layout('components.layouts.public')]
    public function render()
    {
        return view('livewire.dokumen.index');
    }
}
