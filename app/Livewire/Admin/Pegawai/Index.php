<?php

namespace App\Livewire\Admin\Pegawai;
use Livewire\Attributes\Layout;

use Livewire\Component;

class Index extends Component
{
     #[Layout('components.layouts.admin', ['title' => 'Admin | Pegawai', 'pageTitle' => 'Pegawai'])]
    public function render()
    {
        return view('livewire.admin.pegawai.index');
    }
}
