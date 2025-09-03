<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingProgressSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'SUYNL',
            'LIFE CLASS',
            'ENCOUNTER',
            'SOL 1',
            'SOL 2',
            'SOL 3',
            'SOL GRAD',
        ];

        foreach ($types as $index => $type) {
            DB::table('training_progress_types')->updateOrInsert([
                'name' => $type
            ], [
                'id' => $index + 1,
                'name' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
