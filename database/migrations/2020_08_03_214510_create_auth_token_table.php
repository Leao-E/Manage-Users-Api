<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAuthTokenTable extends Migration
{
    public function up()
    {
        Schema::create('auth_token', function (Blueprint $table) {
            $table->string('token', '400')->unique();
            $table->foreignUuid('user_id')->primary();
            $table->dateTimeTz('dt_expire');

            $table->foreign('user_id')
                ->references('id')
                ->on('usr_users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
        DB::unprepared('CREATE EVENT `AUTH_TOKEN_REMOVE_EXPIRED`
                            ON SCHEDULE EVERY 1 HOUR ON COMPLETION NOT
                            PRESERVE ENABLE DO
                            DELETE FROM `auth_token` WHERE `dt_expire` < NOW()');
    }

    public function down()
    {
        DB::unprepared('DROP EVENT IF EXISTS `AUTH_TOKEN_REMOVE_EXPIRED`');
        Schema::dropIfExists('auth_token');
    }
}
