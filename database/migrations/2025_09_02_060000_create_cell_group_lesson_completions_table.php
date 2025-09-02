<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cell_group_lesson_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_attender_id')->constrained('church_attenders')->onDelete('cascade');
            $table->integer('lesson_number');
            $table->date('completion_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cell_group_lesson_completions');
    }
};
