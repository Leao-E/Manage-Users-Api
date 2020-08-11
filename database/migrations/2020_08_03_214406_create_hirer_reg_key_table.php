<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHirerRegKeyTable extends Migration
{
    public function up()
    {
        Schema::create('hre_reg_key', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reg_key');
            $table->string('name');
            $table->foreignUuid('hirer_id');
            $table->dateTime('dt_expire');

            $table->softDeletesTz();

            $table->foreign('hirer_id')
                ->references('id')
                ->on('hre_hirers')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hre_reg_key');
    }
}
