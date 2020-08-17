<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateHirerRegKeyTable extends Migration
{
    public function up()
    {
        Schema::create('hre_reg_key', function (Blueprint $table) {
            $table->uuid('reg_key')->primary();
            $table->string('name');
            $table->foreignUuid('hirer_id');
            $table->foreignUuid('system_id')->nullable();
            $table->dateTime('dt_expire');

            $table->softDeletesTz();

            $table->foreign('hirer_id')
                ->references('id')
                ->on('hre_hirers')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('system_id')
                ->references('id')
                ->on('sys_systems')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
        DB::unprepared('CREATE EVENT `REG_KEY_REMOVE_EXPIRED`
                            ON SCHEDULE EVERY 12 HOUR ON COMPLETION NOT
                            PRESERVE ENABLE DO
                            DELETE FROM `hre_reg_key` WHERE `dt_expire` < NOW()');
    }

    public function down()
    {
        DB::unprepared('DROP EVENT IF EXISTS `REG_KEY_REMOVE_EXPIRED`');
        Schema::dropIfExists('hre_reg_key');
    }
}
