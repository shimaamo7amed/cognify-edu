<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            // Add status column after doc
            $table->string('status')->default('draft')->after('doc');

            // Add created_by column after status
            $table->unsignedBigInteger('created_by')->nullable()->after('status');

            // Add employee_id column after created_by
            $table->unsignedBigInteger('employee_id')->nullable()->after('created_by');
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['status', 'created_by', 'employee_id']);
        });
    }
};
