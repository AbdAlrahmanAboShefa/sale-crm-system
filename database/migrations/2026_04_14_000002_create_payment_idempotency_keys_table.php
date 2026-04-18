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
        Schema::create('payment_idempotency_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('endpoint');
            $table->string('request_hash');
            $table->json('response')->nullable();
            $table->integer('response_code')->nullable();
            $table->timestamp('locked_until')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['key', 'tenant_id']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_idempotency_keys');
    }
};
