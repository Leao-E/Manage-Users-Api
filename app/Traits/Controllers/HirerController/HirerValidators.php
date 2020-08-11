<?php

namespace App\Traits\Controllers\HirerController;

use App\Exceptions\CustomExceptions\ApiException;
use App\Models\Hirer;
use App\Models\User;

trait HirerValidators {
    /*
     * author: Emanuel F.G. Leão
     * resume: Este arquivo, por completo, possui
     * apenas validadores para os campos:
     * - name
     * - user_id
     * - hirer_type
     * O intuito das funções é apenas checar os
     * dados para persistir no banco de dados e
     * não garantir a veracidade dos mesmos
     */


    function validateHirerType($userType){
        if (!is_string($userType) or $userType == null) {
            throw new ApiException('invalid field: usr_type', 400);
        }
        if ($userType !== "ORGAO_PUBLICO" and $userType !== "PESSOA_FISICA"
            and $userType !== "PESSOA_JURIDICA") {

            throw new ApiException('invalid usr_type value: try use
                                \'ORGAO_PUBLICO\' or \'PESSOA_FISICA\' or
                                \'PESSOA_JURIDICA\'', 400);

        }
    }

    function validateUserId($user_id){
        if(!is_string($user_id) or $user_id == null) {
            throw new ApiException('invalid field: user_id', 400);
        } else {
            $user = User::query()->findOrFail($user_id)->get();
            if( $user->isEmpty() ){
                throw new ApiException("invalid value: user_id $user_id does not exists", 400);
            }
        }

        return $user->first();
    }

    function validateCnpj($cnpj){
        if (!is_string($cnpj) or $cnpj == null) {
            throw new \Exception('invalid field: cnpj');
        } else {
            $hirer = Hirer::query()->where('cnpj', $cnpj)->get();
            if ($hirer->isNotEmpty()){
                throw new ApiException("invalid value: cpnj $cnpj already exists", 400);
            }
        }
    }


}
