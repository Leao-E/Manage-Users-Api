<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\Database\CheckInConstraint;

class CreateHirersTable extends Migration
{
    use CheckInConstraint;

    public function up()
    {
        Schema::create('hre_hirers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');

            $table->string('cnpj')->unique()->nullable();

            $table->string('hirer_type');
//            $table->dateTime('dt_expire');
//            $table->string('status');

            $table->foreignUuid('user_id');

            $table->timestampsTz();
            $table->softDeletesTz();

            $table->foreign('user_id')
                ->references('id')
                ->on('usr_users')
                ->cascadeOnDelete();

        });

        // Adicionando as constraints para as colunas do tipo string
        $this->addCheckIn('hre_hirers', 'hirer_type', ['ORGAO_PUBLICO', 'PESSOA_FISICA', 'PESSOA_JURIDICA']);
    }

    public function down()
    {
        Schema::dropIfExists('hre_hirers');
    }
}
