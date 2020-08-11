<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemsTable extends Migration
{
    public function up()
    {
        Schema::create('sys_systems', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');

            $table->unsignedDecimal('storg_size');
            $table->string('storg_path')->unique();

            $table->timestampsTz();
            $table->softDeletesTz();

        });
    }

    public function down()
    {
        Schema::dropIfExists('sys_systems');
    }
}
