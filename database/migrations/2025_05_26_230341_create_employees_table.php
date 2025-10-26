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
        Schema::create('employees', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('date_of_birth');
            $table->string('gender');
            $table->string('nationality');
            $table->string('phone');
            $table->string('email') ->unique();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->text('street_address');
            $table->string('highest_degree');
            $table->string('institution');
            $table->string('graduation_year');
            $table->text('other_institution');
            $table->text('other_degree_title');
            $table->text('other_degree_institution');
            $table->string('other_degree_year');
            $table->text('current_position_title');
            $table->text('current_institution');
            $table->string('current_from');
            $table->string('current_to');
            $table->longText('current_responsibilities');
            $table->longText('previous_position_title');
            $table->text('previous_institution');
            $table->string('previous_from');
            $table->string('previous_to');
            $table->longText('previous_responsibilities');
            $table->string('total_teaching_experience');
            $table->string('special_needs_experience');
            $table->longText('shadow_teacher_experience');
            $table->string('arabic_proficiency');
            $table->string('english_proficiency');
            $table->string('french_proficiency');
            $table->string('italian_proficiency');
            $table->longText('computer_skills');
            $table->text('training_title');
            $table->string('training_date');
            $table->text('professional_memberships');
            $table->longText('motivation');
            $table->longText('suitability');
            $table->enum('is_approved',['accepted','pending','rejected'])->default('pending');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
