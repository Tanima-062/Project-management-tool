<?php

use App\Modals\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email','admin@sme.com')->first();
        if(is_null($user)) {
            $user = new User();
            $user->name = "SME Yousuf";
            $user->email = "admin@sme.com";
            $user->password = Hash::make('123456');
            $user->phone_number = "01620984327";
            $user->designation = "Software Engineer";
            $user->unit = "dev";
            $user->program = "cse";
            $user->role = 'Super Admin ';
            $user->save();

            DB::table('user_programs')->insert(['user_id' => 1, 'program_id' => 1]);
        }
    }
}
