<?php

namespace Database\Seeders;

use App\Models\Bidang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'nama_bidang' => 'Perencanaan'],
            ['id' => 2, 'nama_bidang' => 'Ekonomi Sosial Budaya'],
            ['id' => 3, 'nama_bidang' => 'Fisik dan Prasarana'],
            ['id' => 4, 'nama_bidang' => 'Riset dan Inovasi'],
        ];

        Bidang::insert($data);
    }
}
