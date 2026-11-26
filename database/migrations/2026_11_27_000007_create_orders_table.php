<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->string('shipping_recipient');
            $table->string('shipping_phone', 25);
            $table->text('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_province');
            $table->string('shipping_postal_code', 10);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', [
                'pending',
                'menunggu_pembayaran',
                'menunggu_verifikasi',
                'dibayar',
                'diproses',
                'dikirim',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_pembayaran');
            $table->boolean('stock_deducted')->default(false);
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
