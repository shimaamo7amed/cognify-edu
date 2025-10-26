<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cognify_children_id')->constrained('cognify_children')->onDelete('cascade');
            $table->string('doc');
            $table->string('report_type')->default('observation');
            $table->enum('status', ['draft', 'finalized','sent'])->default('draft');
            $table->foreignId('created_by')->constrained('admins')->onDelete('setnull'); 
            $table->foreignId('employee_id')->constrained('employees')->onDelete('setnull');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
