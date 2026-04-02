<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->decimal('value', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('stage', ['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost'])->default('New');
            $table->integer('probability')->default(0);
            $table->date('expected_close_date')->nullable();
            $table->text('lost_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['stage', 'user_id']);
            $table->index(['contact_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
