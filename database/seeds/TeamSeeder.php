<?php

use Illuminate\Database\Seeder;
use App\Modals\Team;
use App\Modals\Member;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $team = Team::first();

        if(is_null($team)){
            $team = new Team();
            $team->name = 'Design';
            $team->save();

            $member = new Member();
            $member->user_id = 1;
            $member->team_id = $team->id;
            $member->save();
        }
    }
}
