<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('report_date');
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            $table->string('task_name');
            $table->string('task_type'); // マスターテーブルで管理
            $table->integer('planned_hours');
            $table->integer('actual_hours');
            $table->text('remarks')->nullable();
            $table->integer('display_order'); // 表示順を管理
            $table->timestamps();
        });

        Schema::create('master_task_type', function (Blueprint $table) {
            $table->id();
            $table->string('type_code')->unique(); // 種別コード (例: design)
            $table->string('type_name'); // 日本語表記 (例: 設計)
            $table->timestamps();
        });


        Schema::create('report_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_confirmations');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('reports');
    }
}
