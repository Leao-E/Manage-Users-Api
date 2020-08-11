<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // $this->call('UsersTableSeeder');
        $this->call(UsersSeeders::class);
        $this->call(HirersSeeder::class);
        $this->call(SystemSeeder::class);
        $this->call(UserHirerSystemSeeder::class);
        $this->call(HirerSystemsSeeder::class);
    }
}
