<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('label', 50)->default('Rumah');
            $table->string('recipient_name');
            $table->string('phone', 25);
            $table->string('province');
            $table->string('city');
            $table->string('district');
            $table->string('postal_code', 10);
            $table->text('full_address');
            $table->string('notes')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
