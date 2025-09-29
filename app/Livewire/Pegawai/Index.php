<?php

namespace App\Livewire\Pegawai;
use Livewire\Attributes\Layout;

use Livewire\Component;

class Index extends Component
{
    #[Layout('components.layouts.public')]
    public function render()
    {
        return view('livewire.pegawai.index');
    }
}
