<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_salaries', function (Blueprint $table) {
            $table->text('document')->nullable();
            $table->unsignedInteger('payroll_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_salaries', function (Blueprint $table) {
            $table->dropColumn('payroll_id');
            $table->dropColumn('document');
        });
    }
};
