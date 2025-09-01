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
        Schema::create('cell_group_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cell_group_id');
            $table->dateTime('session_date');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('cell_group_id')->references('id')->on('cell_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cell_group_sessions');
    }
};
