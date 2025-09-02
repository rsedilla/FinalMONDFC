<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class NormalizeTrainingProgressTable extends Migration
{
    public function up()
    {
        Schema::table('training_progress', function (Blueprint $table) {
            $table->unsignedBigInteger('training_progress_type_id')->nullable()->after('church_attender_id');
        });

        // Migrate enum progress to training_progress_type_id
        $types = DB::table('training_progress_types')->pluck('id', 'name');
        $progressRows = DB::table('training_progress')->get();
        foreach ($progressRows as $row) {
            $typeId = $types[$row->progress] ?? null;
            if ($typeId) {
                DB::table('training_progress')
                    ->where('id', $row->id)
                    ->update(['training_progress_type_id' => $typeId]);
            }
        }

        Schema::table('training_progress', function (Blueprint $table) {
            $table->dropColumn('progress');
            $table->foreign('training_progress_type_id')->references('id')->on('training_progress_types')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('training_progress', function (Blueprint $table) {
            $table->enum('progress', ['SUYNL', 'LIFECLASS', 'ENCOUNTER', 'SOL 1', 'SOL 2', 'SOL 3', 'SOL Graduate'])->nullable();
            $table->dropForeign(['training_progress_type_id']);
        });

        // Migrate training_progress_type_id back to progress enum
        $types = DB::table('training_progress_types')->pluck('name', 'id');
        $progressRows = DB::table('training_progress')->get();
        foreach ($progressRows as $row) {
            $progress = $types[$row->training_progress_type_id] ?? null;
            if ($progress) {
                DB::table('training_progress')
                    ->where('id', $row->id)
                    ->update(['progress' => $progress]);
            }
        }

        Schema::table('training_progress', function (Blueprint $table) {
            $table->dropColumn('training_progress_type_id');
        });
    }
}
