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
        Schema::create('observation_sessions', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('desc');
            $table->decimal('service_fee', 8, 2);
            $table->decimal('service_tax', 8, 2);
            $table->integer('duration');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observation_sessions');
    }
};
