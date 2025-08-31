<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cell_leaders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('church_attender_id');
            $table->unsignedBigInteger('cell_group_id');
            $table->unsignedBigInteger('training_progress_id');
            $table->timestamps();

            $table->foreign('church_attender_id')->references('id')->on('church_attenders')->onDelete('cascade');
            $table->foreign('cell_group_id')->references('id')->on('cell_groups')->onDelete('cascade');
            $table->foreign('training_progress_id')->references('id')->on('training_progress')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cell_leaders');
    }
};
