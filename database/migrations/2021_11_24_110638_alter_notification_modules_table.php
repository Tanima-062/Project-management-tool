<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNotificationModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_modules', function (Blueprint $table) {
            $table->integer('email')->default('1')->change();
            $table->integer('in_app')->default('1')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_modules', function (Blueprint $table) {
            $table->integer('email')->default('1')->change();
            $table->integer('in_app')->default('1')->change();
        });
    }
}
