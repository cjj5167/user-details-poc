<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 123,
            'username' => 'spartacus',
            'handle' => '@spartacus2018',
            'last_login' => "2018-05-11T00:43:02.322Z",
        ]);
    }
}
