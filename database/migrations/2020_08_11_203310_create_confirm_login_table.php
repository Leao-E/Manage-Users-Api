<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfirmLoginTable extends Migration
{
    public function up()
    {
        Schema::create('confirm_login', function (Blueprint $table) {
            $table->uuid('key')->primary();
            $table->dateTime('expire');
        });
    }

    public function down()
    {
        Schema::dropIfExists('confirm_login');
    }
}
