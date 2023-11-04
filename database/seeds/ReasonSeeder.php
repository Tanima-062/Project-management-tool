<?php

use Illuminate\Database\Seeder;
use App\Modals\Reason;

class ReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reason = Reason::first();

        if(is_null($reason)){
            $reasons=[
                ['name' => 'do.later', 'displayName' => 'Do it later'],
                ['name' => 'wrong.fit', 'displayName' => 'Wrong fit'],
                ['name' => 'not.required', 'displayName' => 'Not required'],
                ['name' => 'others', 'displayName' => 'Others'],
            ];
            DB::table('reasons')->insert($reasons);
        }
    }
}
