<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name'      => 'Admin Website Bapperida',
            'email'     => 'bapperida.pps@gmail.com',
            'password'  => bcrypt('bapperida@2025')
        ]);
        $this->call([
            BidangSeeder::class,
            JabatanSeeder::class,
            TagSeeder::class,
        ]);
    }
}
