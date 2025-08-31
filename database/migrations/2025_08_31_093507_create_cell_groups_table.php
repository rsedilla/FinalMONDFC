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
        Schema::create('cell_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cell_group_type_id');
            $table->string('meeting_time');
            $table->string('meeting_day');
            $table->string('location');
            $table->timestamps();

            $table->foreign('cell_group_type_id')->references('id')->on('cell_group_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cell_groups');
    }
};
