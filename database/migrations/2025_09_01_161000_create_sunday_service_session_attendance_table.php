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
        Schema::create('sunday_service_session_attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sunday_service_session_id');
            $table->unsignedBigInteger('church_attender_id');
            $table->dateTime('attended_at');
            $table->timestamps();

            $table->foreign('sunday_service_session_id', 'sssa_session_id_fk')->references('id')->on('sunday_service_sessions')->onDelete('cascade');
            $table->foreign('church_attender_id', 'sssa_attender_id_fk')->references('id')->on('church_attenders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sunday_service_session_attendance');
    }
};
