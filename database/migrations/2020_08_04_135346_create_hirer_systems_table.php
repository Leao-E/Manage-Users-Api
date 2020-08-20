<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\Database\CheckInConstraint;
use Illuminate\Support\Facades\DB;

class CreateHirerSystemsTable extends Migration
{
    use CheckInConstraint;

    public function up()
    {
        Schema::create('hre_hirer_systems', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('hirer_id');
            $table->foreignUuid('system_id');
            $table->dateTime('dt_expire');
            $table->string('status');

            $table->softDeletesTz();

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
        $this->addCheckIn('hre_hirer_systems', 'status', ['ATIVO', 'INATIVO', 'PENDENTE', 'ATRASADO']);


        /*
         * O link pode ser util
         * https://stackoverflow.com/questions/25346162/difference-between-laravels-raw-sql-functions
         */
        DB::unprepared('CREATE EVENT `HIRER_SYSTEM_SET_STATUS_PENDENTE`
                            ON SCHEDULE EVERY 12 HOUR ON COMPLETION NOT
                            PRESERVE ENABLE DO
                            UPDATE `hre_hirer_systems` SET `status` = \'PENDENTE\' WHERE `status` != \'INATIVO\'
                            AND (`dt_expire` < NOW() AND `dt_expire` >= DATE_SUB(NOW(), INTERVAL 30 DAY))');
        DB::unprepared('CREATE EVENT `HIRER_SYSTEM_SET_STATUS_ATRASADO`
                            ON SCHEDULE EVERY 12 HOUR ON COMPLETION NOT
                            PRESERVE ENABLE DO
                            UPDATE `hre_hirer_systems` SET `status` = \'ATRASADO\' WHERE `status` != \'INATIVO\'
                            AND (`dt_expire` < DATE_SUB(NOW(), INTERVAL 30 DAY))');
    }

    public function down()
    {
        DB::unprepared('DROP EVENT IF EXISTS `HIRER_SYSTEM_SET_STATUS_PENDENTE`');
        DB::unprepared('DROP EVENT IF EXISTS `HIRER_SYSTEM_SET_STATUS_ATRASADO`');
        Schema::dropIfExists('hre_hirer_systems');
    }
}
