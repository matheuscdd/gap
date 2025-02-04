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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id('id');
            $table->string('service', 512);
            $table->integer('km');
            $table->decimal('cost', 10, 2);
            $table->date('date');
            $table->timestamps();
            $table->foreignId('truck')->references('id')->on('trucks')->constrained();
            $table->foreignId('created_by')->references('id')->on('users')->constrained();
            $table->foreignId('updated_by')->references('id')->on('users')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('maintenances');
    }
};
