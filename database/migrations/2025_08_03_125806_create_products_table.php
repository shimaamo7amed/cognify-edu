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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('name');
            $table->json('shortDes')->nullable();
            $table->json('longDes');
            $table->decimal('price', 8, 2);
            $table->decimal('oldPrice', 8, 2);
            $table->boolean('sale')->default(false);
            $table->integer('discountPercentage')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('main_image');
            $table->boolean('inStock')->default(false);
            $table->string('ageRange')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
