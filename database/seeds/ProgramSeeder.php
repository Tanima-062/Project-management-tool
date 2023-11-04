<?php

use Illuminate\Database\Seeder;
use App\Modals\Program;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $program = new Program();
        $program->name = 'General Marketing';
        $program->save();
    }
}
