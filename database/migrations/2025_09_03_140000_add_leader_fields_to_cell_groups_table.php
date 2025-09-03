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
        Schema::table('cell_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('leader_id')->nullable()->after('CellGroupID');
            $table->string('leader_type')->nullable()->after('leader_id'); // 'cell_leader' or 'g12_leader'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cell_groups', function (Blueprint $table) {
            $table->dropColumn(['leader_id', 'leader_type']);
        });
    }
};
