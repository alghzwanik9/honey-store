<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('address')->after('customer_phone')->nullable();
            $table->string('city')->after('address')->nullable();
            $table->string('postal_code')->after('city')->nullable();
            $table->string('payment_method')->after('total_amount')->default('cod'); // cod, card, etc.
            $table->string('payment_status')->after('payment_method')->default('pending'); // pending, paid, failed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['address', 'city', 'postal_code', 'payment_method', 'payment_status']);
        });
    }
};
