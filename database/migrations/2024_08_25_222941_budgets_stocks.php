<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('budgets_stocks', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('budget')->references('id')->on('budgets')->constrained();
            $table->foreignId('stock')->references('id')->on('stocks')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('budgets_stocks');
    }
};
