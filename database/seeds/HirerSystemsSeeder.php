<?php

use Illuminate\Database\Seeder;

class HirerSystemsSeeder extends Seeder
{
    public function run()
    {
        factory(\App\Models\Pivots\HirerSystem::class, 20)->create();
    }
}
