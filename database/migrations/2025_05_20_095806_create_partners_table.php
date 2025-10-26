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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('organizationName');
            $table->string('contactPersonName');
            $table->string('email')->unique();
            $table->string('phoneNumber');
            $table->string('location');
            $table->enum('is_approved',['accepted','pending','rejected'])->default('pending');
            $table->foreignId('service_id')->references('id')->on('services')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
