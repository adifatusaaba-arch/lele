<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('shipping_method', 50);
            $table->string('courier_name')->nullable();
            $table->string('tracking_number')->nullable();
            $table->decimal('cost', 12, 2)->default(0);
            $table->string('estimated_delivery')->nullable();
            $table->enum('status', ['pending', 'disiapkan', 'dikirim', 'diterima'])->default('pending');
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping');
    }
};
