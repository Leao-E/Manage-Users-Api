<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemVersionsTable extends Migration
{
    public function up()
    {
        Schema::create('system_versions', function (Blueprint $table) {
            $table->foreignUuid('system_id');
            $table->boolean('production');
            $table->string('version');
            $table->text('changelog');
            $table->timestamps();

            $table->foreign('system_id')
                ->references('id')
                ->on('sys_systems')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_versions');
    }
}
