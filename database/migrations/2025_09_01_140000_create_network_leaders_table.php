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
        Schema::create('network_leaders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('church_attender_id')->nullable();
            $table->enum('network', ['mens', 'womens']);
            $table->timestamps();

            $table->foreign('church_attender_id')->references('id')->on('church_attenders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_leaders');
    }
};
