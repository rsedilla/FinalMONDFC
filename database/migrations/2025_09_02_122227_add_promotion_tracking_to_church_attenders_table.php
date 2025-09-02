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
        Schema::table('church_attenders', function (Blueprint $table) {
            $table->timestamp('promoted_at')->nullable()->after('updated_at');
            $table->string('promoted_to')->nullable()->after('promoted_at');
            $table->unsignedBigInteger('promoted_to_id')->nullable()->after('promoted_to');
            
            // Add index for performance on queries filtering non-promoted members
            $table->index('promoted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('church_attenders', function (Blueprint $table) {
            $table->dropIndex(['promoted_at']);
            $table->dropColumn(['promoted_at', 'promoted_to', 'promoted_to_id']);
        });
    }
};
