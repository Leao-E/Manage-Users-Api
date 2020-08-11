<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\Database\CheckInConstraint;

class CreateHirerSystemsTable extends Migration
{
    use CheckInConstraint;

    public function up()
    {
        Schema::create('hre_hirer_systems', function (Blueprint $table) {
            $table->foreignUuid('hirer_id');
            $table->foreignUuid('system_id');
            $table->dateTime('dt_expire');
            $table->string('status');

            $table->softDeletesTz();

            $table->primary(['hirer_id', 'system_id'], 'hirer_systems_key');

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
    }

    public function down()
    {
        Schema::dropIfExists('hre_hirer_systems');
    }
}
