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
        Schema::create('g12_leader_cell_member', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('g12_leader_id');
            $table->unsignedBigInteger('cell_member_id');
            $table->timestamps();

            $table->foreign('g12_leader_id')->references('id')->on('g12_leaders')->onDelete('cascade');
            $table->foreign('cell_member_id')->references('id')->on('cell_members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('g12_leader_cell_member');
    }
};
