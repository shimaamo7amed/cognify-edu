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
        Schema::table('observation_reports', function (Blueprint $table) {
            $table->string('delivery_status')->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->unsignedBigInteger('sent_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('observation_reports', function (Blueprint $table) {
            //
        });
    }
};
