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
        Schema::create('g12_leader_cell_leader', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('g12_leader_id');
            $table->unsignedBigInteger('cell_leader_id');
            $table->timestamps();

            $table->foreign('g12_leader_id')->references('id')->on('g12_leaders')->onDelete('cascade');
            $table->foreign('cell_leader_id')->references('id')->on('cell_leaders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('g12_leader_cell_leader');
    }
};
