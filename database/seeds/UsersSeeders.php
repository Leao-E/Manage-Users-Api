<?php

use Illuminate\Database\Seeder;

class UsersSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dt_birth = \Illuminate\Support\Carbon::create(2000, 4, 4, 0,0,0);

        (new \App\Models\User([
            'name' => 'Emanuel Felipe Gomes Leão',
            'unq_nick' => 'Leao-E',
            'email' => 'eemanuelleao@gmail.com',
            'usr_type' => 'PESSOA_FISICA',
            'cpf' => '05763141458',
            'dt_birth' => $dt_birth,
            'is_sudo' => true,
            'password' => 'd3c0l3',
        ]))->save();

        $dt_birth = \Illuminate\Support\Carbon::create(1988,01,16,0,0,0);

        (new \App\Models\User([
            'name' => 'Giliard Faustino da Silva',
            'unq_nick' => 'Gili-F',
            'email' => 'giliardfaustino@hotmail.com',
            'usr_type' => 'PESSOA_FISICA',
            'cpf' => '06707571442',
            'dt_birth' => $dt_birth,
            'is_sudo' => true,
            'password' => 'd3c0l3',
        ]))->save();

        $dt_birth = \Illuminate\Support\Carbon::create(2000,3,15,0,0,0);

        (new \App\Models\User([
            'name' => 'Johnny Reberty Alves Oliveira',
            'unq_nick' => 'John-R',
            'email' => 'jralvesoliveira15@gmail.com',
            'usr_type' => 'PESSOA_FISICA',
            'cpf' => '01747446416',
            'dt_birth' => $dt_birth,
            'is_sudo' => true,
            'password' => 'd3c0l3',
        ]))->save();
    }
}
