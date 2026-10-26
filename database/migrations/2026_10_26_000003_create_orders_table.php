<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->string('queue_number');
            $table->string('customer_name');
            $table->enum('order_type', ['dine_in', 'takeaway']);
            $table->string('table_number')->nullable();
            $table->uuid('guest_token')->unique();
            $table->decimal('total_amount', 12, 2);
            $table->string('payment_status')->default('pending');
            $table->string('order_status')->default('pending');
            $table->timestamps();

            $table->index(['created_at', 'queue_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
