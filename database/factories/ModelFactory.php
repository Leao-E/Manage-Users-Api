<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(\App\Models\User::class, function (Faker $faker){
    $user_types = ['PESSOA_FISICA', 'PESSOA_JURIDICA'];

    return[
        'id' => $faker->uuid,
        'name' => $faker->name,
        'email'=> $faker->unique()->email,
        'unq_nick' => $faker->unique()->userName,
        'usr_type' => $user_types[rand(0,1)],
        'cpf' => $faker->unique()->numberBetween(10000000000, 99999999999),
        'cnpj' => $faker->unique()->numberBetween(100000000000000, 999999999999999),
        'dt_birth' => $faker->dateTimeBetween('1960-01-01', '2016-12-31')->format('Y-m-d'),
        'is_sudo' => rand(0,1),
        'is_hirer' => rand(0,1),
        'password' => '1234',
    ];
});

$factory->define(\App\Models\Hirer::class, function (Faker $faker){
    $hyrer_type = ['ORGAO_PUBLICO', 'PESSOA_FISICA', 'PESSOA_JURIDICA'];

    $users_id = \App\Models\User::pluck('id')->toArray();

    return[
        'id' => $faker->uuid,
        'name' => $faker->company,
        'hirer_type' => $hyrer_type[rand(0,2)],
        'cnpj' => $faker->unique()->numberBetween(100000000000000, 999999999999999),
        'user_id' => $faker->randomElement($users_id),

    ];
});

$factory->define(\App\Models\System::class, function (Faker $faker){
    return [
        'id' => $faker->uuid,
        'name' => $faker->word,
        'storg_path' => $faker->unique()->url,
        'storg_size' => $faker->randomFloat(2, 0, 1000),
    ];
});

$factory->define(\App\Models\Pivots\UserHirerSystems::class, function (Faker $faker){
    $system = \App\Models\System::pluck('id')->toArray();
    $user = \App\Models\User::pluck('id')->toArray();
    $hirer = \App\Models\Hirer::pluck('id')->toArray();

    return [
        'user_id' => $faker->randomElement($user),
        'hirer_id' => $faker->randomElement($hirer),
        'system_id' => $faker->randomElement($system),
    ];
});

$factory->define(\App\Models\Pivots\HirerSystem::class, function (Faker $faker){
    $status = ['ATIVO', 'INATIVO', 'PENDENTE', 'ATRASADO'];

    $hirer = \App\Models\Hirer::pluck('id')->toArray();
    $system = \App\Models\System::pluck('id')->toArray();

    return [
        'hirer_id' => $faker->randomElement($hirer),
        'system_id' => $faker->randomElement($system),
        'status' => $status[rand(0, 3)],
        'dt_expire' => $faker->dateTimeBetween('2020-01-01', '2022-12-31')->format('Y-m-d')
    ];
});
