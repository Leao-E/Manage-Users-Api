<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersHirersSystemsTable extends Migration
{
    public function up()
    {
        Schema::create('usr_user_hirer_systems', function (Blueprint $table) {
            $table->foreignUuid('user_id');
            $table->foreignUuid('hirer_id');
            $table->foreignUuid('system_id');
            $table->softDeletesTz();

            $table->primary(['user_id', 'hirer_id', 'system_id'], 'user_hirer_system_key');

            $table->foreign('user_id')
                ->references('id')
                ->on('usr_users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('hirer_id')
                ->references('id')
                ->on('hre_hirers')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('system_id')
                ->references('id')
                ->on('sys_systems')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usr_user_hirer_systems');
    }
}
