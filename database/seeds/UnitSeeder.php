<?php

use Illuminate\Database\Seeder;
use App\Modals\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unit = new Unit();
        $unit->name = 'Bikroy.com';
        $unit->save();
    }
}
