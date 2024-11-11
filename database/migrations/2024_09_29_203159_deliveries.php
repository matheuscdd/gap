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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('client')->references('id')->on('clients')->constrained();
            $table->date('delivery_date');
            $table->date('receipt_date')->nullable();
            $table->string('driver', 128);
            $table->string('invoice', 44)->nullable();
            $table->string('delivery_address', 256);
            $table->string('provider_name', 128)->nullable();
            $table->string('provider_city', 128)->nullable();
            $table->enum('unloaded', ['client', 'carrier']);
            $table->enum('payment_status', ['paid', 'pending'])->nullable();
            $table->enum('payment_method', ['pix', 'ticket'])->nullable();
            $table->date('payment_date')->nullable();
            $table->double('revenue')->nullable();
            $table->double('travel_cost')->nullable();
            $table->double('unloading_cost');
            $table->boolean('finished')->default(false);
            $table->foreignId('ref')->nullable()->references('id')->on('deliveries')->constrained();
            $table->timestamps();
            $table->foreignId('created_by')->references('id')->on('users')->constrained();
            $table->foreignId('updated_by')->references('id')->on('users')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('budgets');
    }
};
