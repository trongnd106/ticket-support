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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description'); 
            $table->enum('priority', ['low', 'medium', 'high'])->default('low'); 
            $table->enum('status', ['pending', 'progress', 'resolved'])->default('pending');
            $table->boolean('is_locked')->default(0); 
            $table->foreignId('assigned_to')->nullable()
                ->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
