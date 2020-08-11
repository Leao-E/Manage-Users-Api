<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthTokenTable extends Migration
{
    public function up()
    {
        Schema::create('auth_token', function (Blueprint $table) {
            $table->string('token', '400')->primary();
            $table->foreignUuid('user_id')->unique();
            $table->dateTimeTz('dt_expire');

            $table->foreign('user_id')
                ->references('id')
                ->on('usr_users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auth_token');
    }
}
