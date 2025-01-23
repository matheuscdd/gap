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
        Schema::create('deliveries_stocks', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('delivery')->references('id')->on('deliveries')->constrained()->onDelete('cascade');
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
