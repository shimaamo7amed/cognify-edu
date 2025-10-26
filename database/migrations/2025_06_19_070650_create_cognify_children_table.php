<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cognify_children', function (Blueprint $table) {
            $table->id();
            $table->string('childPhoto')->nullable();
            $table->string('fullName');
            $table->integer('age');
            $table->string('schoolName');
            $table->string('schoolAddress')->nullable();
            $table->string('homeAddress');
            $table->string('gender')->nullable();
            $table->longText('textDescription')->nullable();
            $table->string('voiceRecording')->nullable();
            $table->json('foodAllergies')->nullable();
            $table->json('environmentalAllergies')->nullable();
            $table->json('severityLevels')->nullable();
            $table->json('medicationAllergies')->nullable();
            $table->json('medicalConditions')->nullable();
            $table->enum('priority', ['high', 'medium', 'low','Intensive'])->nullable();
            $table->foreignId('parent_id')->references('id')->on('cognify_parents')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('cognify_children');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};