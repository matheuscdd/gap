<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void {
        Schema::create('users', function(Blueprint $table) {
            $table->id('id');
            $table->string('name', 64);
            $table->string('email', 255)->unique();
            $table->string('password', 64);
            $table->enum('type', ['admin', 'common']);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('users');
    }
};
