<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Preserve legacy votes: stop for reconciliation instead of deleting duplicates.
        if (DB::table('poll_votes')->select('poll_id', 'user_id')->groupBy('poll_id', 'user_id')->havingRaw('COUNT(*) > 1')->exists()) {
            throw new RuntimeException('Duplicate poll/user votes exist. Reconcile them before applying polling rules; no votes were removed.');
        }
        Schema::table('poll_votes', fn (Blueprint $table) => $table->unique(['poll_id', 'user_id']));
        Schema::table('polls', function (Blueprint $table) {
            $table->string('result_visibility', 20)->default('all');
        });
    }

    public function down(): void
    {
        Schema::table('polls', fn (Blueprint $table) => $table->dropColumn('result_visibility'));
        Schema::table('poll_votes', fn (Blueprint $table) => $table->dropUnique(['poll_id', 'user_id']));
    }
};
