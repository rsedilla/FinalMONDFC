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
        // Delete SUYNL since LIFECLASS already exists
        DB::table('training_progress_types')
            ->where('name', 'SUYNL')
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate SUYNL entry
        DB::table('training_progress_types')->insert([
            'name' => 'SUYNL',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
