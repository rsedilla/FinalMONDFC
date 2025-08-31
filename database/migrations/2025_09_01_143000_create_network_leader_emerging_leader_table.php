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
        Schema::create('network_leader_emerging_leader', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('network_leader_id');
            $table->unsignedBigInteger('church_attender_id');
            $table->timestamps();

            $table->foreign('network_leader_id')->references('id')->on('network_leaders')->onDelete('cascade');
            $table->foreign('church_attender_id')->references('id')->on('church_attenders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_leader_emerging_leader');
    }
};
