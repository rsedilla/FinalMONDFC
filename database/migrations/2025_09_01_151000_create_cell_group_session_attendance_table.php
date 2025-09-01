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
        Schema::create('cell_group_session_attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cell_group_session_id');
            $table->unsignedBigInteger('church_attender_id');
            $table->dateTime('attended_at');
            $table->timestamps();

            $table->foreign('cell_group_session_id')->references('id')->on('cell_group_sessions')->onDelete('cascade');
            $table->foreign('church_attender_id')->references('id')->on('church_attenders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cell_group_session_attendance');
    }
};
