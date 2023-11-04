<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColoumnTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('team_lead_id');
            $table->dropColumn('team_lead_from');
            $table->dropColumn('create_by_id');
            $table->dropColumn('flag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('team_lead_id');
            $table->dropColumn('team_lead_from');
            $table->dropColumn('create_by_id');
            $table->dropColumn('flag');
        });
    }
}
