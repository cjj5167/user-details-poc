<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CircuitBreakersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('circuit_breakers')->insert([
            "name" => "soci.al"
        ]);
    }
}
