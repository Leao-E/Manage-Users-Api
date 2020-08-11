<?php

namespace App\Traits\Models;

/*
 * author: Emanuel F.G. Leão
 * resume: esse trait é pensado para substituir
 * o id convencional do laravel/lumen pelo uuid
 * ao usar ele numa classe que extende Model
 * ele automaticamente criar um uuid quando
 * o model for persistido no banco
 */

use Illuminate\Support\Str;

trait UsesUUID {

    /*
     * Método de boot do model, cria o uuid
     * para os novos models persistidos no
     * banco de dados
     */
    public static function boot (){
        parent::boot();
        self::creating(function ($model){
            $model->id = Str::uuid()->toString();
        });
    }
    /*
     * retorna se o id é ou não auto-increment
     */
    public function getIncrementing()
    {
        return false;
    }
    /*
     * retorna o nome da coluna do id
     */
    public function getKeyName()
    {
        return 'id';
    }
    /*
     * retorna o tipo da coluna do id
     */
    public function getKeyType()
    {
        return 'string';
    }
}
