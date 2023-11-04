<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('assign_by_id');
            $table->string('assign_to');
            $table->string('task_id')->nullable();
            $table->string('title');
            $table->string('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('priority');
            $table->integer('parent_id')->default('0');
            $table->integer('status')->default('0');
            $table->integer('main_parent_id')->default('0');
            $table->integer('request_from');
            $table->integer('request_to');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_tasks');
    }
}
