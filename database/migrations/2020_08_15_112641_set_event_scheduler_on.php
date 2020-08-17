<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class SetEventSchedulerOn extends Migration
{
    public function up()
    {
        DB::statement('SET GLOBAL event_scheduler = ON');
    }

    public function down()
    {
        DB::statement('SET GLOBAL event_scheduler = OFF');
    }
}
