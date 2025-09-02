<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingProgressSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'LIFECLASS',
            'ENCOUNTER',
            'SOL 1',
            'SOL 2',
            'SOL 3',
            'SOL GRAD',
        ];

        foreach ($types as $type) {
            DB::table('training_progress_types')->updateOrInsert([
                'name' => $type
            ], [
                'name' => $type
            ]);
        }
    }
}
