<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\G12Leader;

class G12LeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaders = [
            ['name' => 'Oriel Ballano'],
            ['name' => 'Geraldine Ballano'],
        ];

        foreach ($leaders as $leader) {
            G12Leader::updateOrCreate(['name' => $leader['name']]);
        }
    }
}
