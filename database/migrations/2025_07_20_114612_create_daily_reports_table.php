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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('cognify_children')->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->string('employee_name')->nullable();
            $table->date('report_date')->nullable();
            $table->text('attention_activity')->nullable();
            $table->text('attention_level')->nullable();
            $table->text('positive_behavior')->nullable();
            $table->text('communication')->nullable();
            $table->text('social_interaction')->nullable();
            $table->text('meltdown')->nullable();
            $table->text('general_behavior')->nullable();
            $table->text('reinforcers')->nullable();
            $table->text('academic')->nullable();
            $table->text('independence')->nullable();
            $table->tinyInteger('overall_rating')->nullable(); // 1 to 5
            $table->text('comments')->nullable();
            $table->enum('status', ['draft', 'finalized'])->default('draft');
            $table->enum('report_type', ['daily', 'observation'])->default('daily');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
