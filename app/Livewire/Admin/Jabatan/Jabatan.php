<?php

namespace App\Livewire\Admin\Jabatan;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Jabatan extends Component
{
    #[Layout('components.layouts.admin', ['title' => 'Admin | Jabatan', 'pageTitle' => 'Jabatan'])]
    public function render()
    {
        return view('livewire.admin.jabatan.jabatan');
    }
}
