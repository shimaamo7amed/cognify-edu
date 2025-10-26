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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('child_id');
            $table->unsignedBigInteger('session_id');

            $table->string('transaction_id')->nullable();
            $table->string('status')->default('pending'); // pending, paid, failed
            $table->decimal('amount', 10, 2);
            $table->json('metadata')->nullable();
            $table->string('reference_id')->unique()->nullable();

            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('cognify_parents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
