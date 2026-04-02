<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['Call', 'Meeting', 'Email', 'Task', 'Demo']);
            $table->text('note');
            $table->enum('outcome', ['Positive', 'Neutral', 'Negative'])->nullable();
            $table->dateTime('due_date')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->boolean('is_done')->default(false);
            $table->timestamps();

            $table->index(['contact_id', 'due_date']);
            $table->index(['user_id', 'is_done']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
