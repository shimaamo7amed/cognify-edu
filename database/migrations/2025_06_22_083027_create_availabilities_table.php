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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('interval', [15, 30, 60])->default(30); // Time slot interval in minutes
            $table->enum('status', ['available', 'booked', 'blocked'])->default('available');
            $table->string('recurrence_type')->nullable(); // weekly, bi-weekly, monthly
            $table->json('recurrence_days')->nullable(); // Store days for recurring schedules
            $table->date('recurrence_end_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Add indexes for better performance
            $table->index(['start_time', 'end_time']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
