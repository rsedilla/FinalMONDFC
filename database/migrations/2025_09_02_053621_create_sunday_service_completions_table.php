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
        Schema::create('sunday_service_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_attender_id')->constrained('church_attenders')->onDelete('cascade');
            $table->integer('service_number');
            $table->date('attendance_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sunday_service_completions');
    }
};
