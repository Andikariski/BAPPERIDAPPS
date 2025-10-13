<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'nama_jabatan' => 'Kepala Badan', 'kelas_jabatan' => 1],
            ['id' => 2, 'nama_jabatan' => 'Sekretaris', 'kelas_jabatan' => 2],
            ['id' => 3, 'nama_jabatan' => 'Kepala Bidang', 'kelas_jabatan' => 3],
            ['id' => 4, 'nama_jabatan' => 'Kepala Sub Bidang', 'kelas_jabatan' => 4],
            ['id' => 5, 'nama_jabatan' => 'Staff', 'kelas_jabatan' => 5],
        ];

        Jabatan::insert($data);
    }
}
