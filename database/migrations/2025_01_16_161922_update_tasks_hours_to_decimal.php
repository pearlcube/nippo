<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTasksHoursToDecimal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->decimal('planned_hours', 8, 2)->change(); // 少数2桁に変更
            $table->decimal('actual_hours', 8, 2)->change();  // 少数2桁に変更
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('planned_hours')->change(); // 元の型に戻す
            $table->integer('actual_hours')->change();
        });
    }
}
