<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateConfirmLoginTable extends Migration
{
    public function up()
    {
        Schema::create('confirm_login', function (Blueprint $table) {
            $table->uuid('key')->primary();
            $table->dateTime('expire');
        });
        DB::unprepared('CREATE EVENT `CONFIRM_LOGIN_REMOVE_EXPIRED`
                            ON SCHEDULE EVERY 3 HOUR ON COMPLETION NOT
                            PRESERVE ENABLE DO
                            UPDATE `hre_hirer_systems` SET `status` = \'ATRASADO\'
                            WHERE (`dt_expire` < DATE_SUB(NOW(), INTERVAL 30 DAY))');
    }

    public function down()
    {
        DB::unprepared('DROP EVENT IF EXISTS `CONFIRM_LOGIN_REMOVE_EXPIRED`');
        Schema::dropIfExists('confirm_login');
    }
}
