<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->softDeletes();
            $table->index(['class_id', 'event_date']);
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['class_id', 'event_date']);
            $table->dropSoftDeletes();
            $table->dropColumn(['ends_at', 'cancelled_at']);
        });
    }
};
