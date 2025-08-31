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
        Schema::create('training_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('church_attender_id');
            $table->enum('progress', ['SUYNL', 'LIFECLASS', 'ENCOUNTER', 'SOL 1', 'SOL 2', 'SOL 3', 'SOL Graduate']);
            $table->timestamps();

            $table->foreign('church_attender_id')->references('id')->on('church_attenders')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_progress');
    }
};
