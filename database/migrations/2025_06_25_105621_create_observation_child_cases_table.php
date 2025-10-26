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
        Schema::create('observation_child_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('cognify_children')->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->foreignId('observation_session_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 8, 2);
            // $table->
            $table->enum('status', ['new_request', 'under_review', 'approved','schedualed','assigned', 'completed', 'cancelled'])->default('new_request');
            $table->integer('duration')->nullable();
            $table->date('slot_date');
            $table->time('slot_time');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observation_child_cases');
    }
};