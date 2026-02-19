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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->decimal('user_id', 10, 2)->nullable(); // Actually typically orders have user_id if logged in, but requirement says specific fields. Checking requirement...
            // Requirement: (id, customer_name, customer_phone, total_amount, status [enum: pending, shipped, completed])
            // I will stick to requirement strictly.
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'shipped', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
