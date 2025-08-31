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
        Schema::create('network_leader_cell_leader', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('network_leader_id');
            $table->unsignedBigInteger('cell_leader_id')->unique();
            $table->timestamps();

            $table->foreign('network_leader_id')->references('id')->on('network_leaders')->onDelete('cascade');
            $table->foreign('cell_leader_id')->references('id')->on('cell_leaders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_leader_cell_leader');
    }
};
