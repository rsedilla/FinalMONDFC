<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CivilStatus;

class CivilStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'Single',
            'Married',
            'Divorced',
            'Widowed',
            'Separated'
        ];

        foreach ($statuses as $status) {
            CivilStatus::create(['name' => $status]);
        }
    }
}
