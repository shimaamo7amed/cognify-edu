<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('observation_reports', function (Blueprint $table) {
            $table->id();
            $table->text('childs_strengths')->nullable();
            $table->text('areas_of_concern')->nullable();
            $table->text('behavioral_observations')->nullable();
            $table->text('academic_performance')->nullable();
            $table->text('social_interactions')->nullable();
            $table->text('cognitive_assessment')->nullable();
            $table->text('emotional_assessment')->nullable();
            $table->text('physical_assessment')->nullable();
            $table->text('communication_skills')->nullable();
            $table->text('classroom_environment')->nullable();
            $table->text('teacher_interaction')->nullable();
            $table->text('peer_interaction')->nullable();
            $table->text('professional_recommendations')->nullable();
            $table->text('short_term_goals')->nullable();
            $table->text('long_term_goals')->nullable();
            $table->text('intervention_strategies')->nullable();
            $table->text('classroom_accommodations')->nullable();
            $table->text('next_steps')->nullable();
            $table->dateTime('recommended_follow_up_date')->nullable();
            $table->enum('parent_meeting_required', ['urgent-within--weeks', 'urgent-within-1-weeks', 'soon-within-2-weeks', 'routine-within-1-month','not-required'])->nullable()->default('not-required');
            $table->enum('status', ['draft','finalized'])->nullable()->default('draft');
            $table->unsignedTinyInteger('progress')->nullable()->default(0);
            $table->foreignId('child_id')->nullable()->constrained('cognify_children')->onDelete('cascade');
            $table->foreignId('observation_child_case_id')->nullable()->constrained('observation_child_cases')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observation_reports');
    }
};
