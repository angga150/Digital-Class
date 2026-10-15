<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('nickname', 50)->nullable();
            $table->string('avatar_path')->nullable();
            $table->text('bio')->nullable();
            $table->json('hobby')->nullable();
            $table->json('favorite')->nullable();
            $table->text('fun_fact')->nullable();
            $table->date('birth_date')->nullable();
            $table->boolean('birth_year_visible')->default(false);
            $table->string('birthday_visibility')->default('class_only');
            $table->string('profile_visibility')->default('class_only');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
