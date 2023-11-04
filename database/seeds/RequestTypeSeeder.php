<?php

use Illuminate\Database\Seeder;
use App\Modals\RequestType;

class RequestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $request_type = RequestType::first();

        if(is_null($request_type)){
            $request_types=[
                ['name' => 'sick.leave', 'displayName' => 'Sick Leave'],
                ['name' => 'annual.leave', 'displayName' => 'Annual Leave'],
                ['name' => 'engage.with.other.priority.tasks', 'displayName' => 'Engage with other Priority Tasks'],
                ['name' => 'additional.information', 'displayName' => 'Additional information'],
                ['name' => 'time.extension', 'displayName' => 'Time extension'],
                ['name' => 'others', 'displayName' => 'Others'],
            ];
            DB::table('request_types')->insert($request_types);
        }
    }
}
