<?php

namespace App\Livewire\Admin\RAP;

use Livewire\Attributes\Layout;
use Livewire\Component;

class RAP extends Component
{
    #[Layout('components.layouts.admin', ['title' => 'Admin | RAP', 'pageTitle' => 'RAP (Rencana Anggaran Penggunaan)'])]
    public function render()
    {
        return view('livewire.admin.r-a-p.r-a-p');
    }
}
