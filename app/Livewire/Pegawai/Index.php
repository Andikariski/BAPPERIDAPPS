<?php

namespace App\Livewire\Pegawai;

use App\Models\Pegawai;
use Livewire\Attributes\Layout;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Layout('components.layouts.public')]
    public function render()
    {
        $dataPegawai = Pegawai::query()
            ->with(['bidang', 'jabatan'])
            ->paginate(8);

        return view('livewire.pegawai.index', [
            'dataPegawai' => $dataPegawai
        ]);
    }
}
