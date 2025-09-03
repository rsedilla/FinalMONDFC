<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure Raymond Sedilla exists in church_attenders
        $attenderId = DB::table('church_attenders')->where([
            ['first_name', '=', 'Raymond'],
            ['last_name', '=', 'Sedilla'],
        ])->value('id');

        if (!$attenderId) {
            $attenderId = DB::table('church_attenders')->insertGetId([
                'first_name' => 'Raymond',
                'middle_name' => '',
                'last_name' => 'Sedilla',
                'email' => 'raymond.sedilla@example.com',
                'phone_number' => '',
                'network' => 'mens',
                'present_address' => '',
                'permanent_address' => '',
                'civil_status_id' => 1,
                'birthday' => '1990-01-01',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Set training progress to SOL GRAD (ID: 7)
        $progress = DB::table('training_progress')->where('church_attender_id', $attenderId)->first();
        if ($progress) {
            DB::table('training_progress')->where('id', $progress->id)->update([
                'training_progress_type_id' => 7,
                'updated_at' => now(),
            ]);
            $trainingProgressId = $progress->id;
        } else {
            $trainingProgressId = DB::table('training_progress')->insertGetId([
                'church_attender_id' => $attenderId,
                'training_progress_type_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Add to g12_leaders table
        $exists = DB::table('g12_leaders')->where('church_attender_id', $attenderId)->exists();
        if (!$exists) {
            DB::table('g12_leaders')->insert([
                'church_attender_id' => $attenderId,
                'cell_group_id' => 7, // Assign to existing cell group
                'training_progress_id' => $trainingProgressId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Optionally remove Raymond Sedilla from g12_leaders and training_progress
        $attenderId = DB::table('church_attenders')->where([
            ['first_name', '=', 'Raymond'],
            ['last_name', '=', 'Sedilla'],
        ])->value('id');
        if ($attenderId) {
            DB::table('g12_leaders')->where('church_attender_id', $attenderId)->delete();
            DB::table('training_progress')->where('church_attender_id', $attenderId)->delete();
        }
    }
};
