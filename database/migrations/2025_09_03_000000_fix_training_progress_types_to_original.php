<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing training progress types
        DB::table('training_progress_types')->truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Insert the original 7 training progress types with specific IDs
        $types = [
            ['id' => 1, 'name' => 'SUYNL'],
            ['id' => 2, 'name' => 'LIFE CLASS'],
            ['id' => 3, 'name' => 'ENCOUNTER'],
            ['id' => 4, 'name' => 'SOL 1'],
            ['id' => 5, 'name' => 'SOL 2'],
            ['id' => 6, 'name' => 'SOL 3'],
            ['id' => 7, 'name' => 'SOL GRAD'],
        ];

        foreach ($types as $type) {
            DB::table('training_progress_types')->insert([
                'id' => $type['id'],
                'name' => $type['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be easily reversed
        // as it would require knowing the previous state
    }
};
