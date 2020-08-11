<?php

use Illuminate\Database\Seeder;

class HirersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Hirer::class, 10)->create();
    }
}
