<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        User::create([
            'name' => 'paulboco',
            'email' => 'foo@bar.com',
            'password' => Hash::make('asdfasdf'),
        ]);
    }
}