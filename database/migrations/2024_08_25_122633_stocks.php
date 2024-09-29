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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 512);
            $table->integer('weight');
            $table->integer('quantity');
            $table->string('extra')->nullable();
            $table->timestamps();
            $table->foreignId('type')->references('id')->on('stock_type')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('stocks');
    }
};
