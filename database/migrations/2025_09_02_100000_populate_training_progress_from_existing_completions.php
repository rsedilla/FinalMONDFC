<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class PopulateTrainingProgressFromExistingCompletions extends Migration
{
    public function up()
    {
        // Get all church attenders who have completed SUYNL lessons
        $suynlCompletions = DB::table('suynl_lesson_completions')
            ->select('church_attender_id')
            ->groupBy('church_attender_id')
            ->get();

        foreach ($suynlCompletions as $completion) {
            // Check if training progress record already exists
            $exists = DB::table('training_progress')
                ->where('church_attender_id', $completion->church_attender_id)
                ->where('progress', 'SUYNL')
                ->exists();

            if (!$exists) {
                // Create training progress record
                DB::table('training_progress')->insert([
                    'church_attender_id' => $completion->church_attender_id,
                    'progress' => 'SUYNL',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down()
    {
        // Remove training progress records that were created by this migration
        DB::table('training_progress')
            ->where('progress', 'SUYNL')
            ->delete();
    }
}
