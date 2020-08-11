<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\Database\CheckInConstraint;

class CreateUsersTable extends Migration
{
    use CheckInConstraint;

    public function up()
    {
        Schema::create('usr_users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('unq_nick')->unique();
            $table->string('email')->unique();
            $table->string('usr_type');

            $table->string('name');

            $table->string('cpf')->unique()->nullable();
            $table->string('cnpj')->unique()->nullable();

            $table->dateTime('dt_birth');

            $table->boolean('is_sudo')->default(0);
            $table->boolean('is_hirer')->default(0);

            $table->string('password');

            $table->timestampsTz();
            $table->softDeletesTz();
        });

        // Adicionando as constraints para as colunas do tipo string
        $this->addCheckIn('usr_users', 'usr_type', ['PESSOA_FISICA', 'PESSOA_JURIDICA']);

    }

    public function down()
    {
        Schema::dropIfExists('usr_users');
    }
}
