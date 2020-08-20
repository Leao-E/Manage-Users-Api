<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHirerSystemToUsersHiresSystemsTable extends Migration
{
    public function up()
    {
        Schema::table('usr_user_hirer_systems', function (Blueprint $table) {

            $table->foreignUuid('hirer_system_id')->after('user_id');

            $table->foreign('hirer_system_id')
                ->references('id')
                ->on('hre_hirer_systems')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->primary(['user_id', 'hirer_system_id'], 'user_hirer_system_key');
        });
    }

    public function down()
    {
        Schema::table('usr_user_hirer_systems', function (Blueprint $table) {
            $table->dropForeign(['hirer_system_id']);
            $table->dropForeign(['user_id']);

            $table->dropPrimary();

            $table->dropColumn('hirer_system_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('usr_users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }
}
