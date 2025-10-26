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
        Schema::create('session_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('observation_session_id')->on('observation_sessions')->constrained()->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('interval')->default(30)->comment('Interval in minutes');
            $table->boolean('is_booked')->default(false);
            $table->string('recurrence_type')->nullable(); // weekly, bi-weekly, monthly
            $table->json('recurrence_days')->nullable(); // Store days for recurring schedules
            $table->date('recurrence_end_date')->nullable();
            // Add indexes for better performance
            $table->index(['start_time', 'end_time']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_slots');
    }
};
