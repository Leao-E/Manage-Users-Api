<?php

use Illuminate\Database\Seeder;

class UserHirerSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Pivots\UserHirerSystems::class, 20)->create();
    }
}
