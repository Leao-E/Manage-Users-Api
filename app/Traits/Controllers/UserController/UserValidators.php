<?php
namespace App\Traits\Controllers\UserController;

use App\Exceptions\CustomExceptions\ApiException;
use App\Models\User;

trait UserValidators {
    /*
     * author: Emanuel F.G. Leão
     * resume: Este arquivo, por completo, possui
     * apenas validadores para os campos:
     * - unq_nick
     * - email
     * - usr_type
     * - name
     * - dt_birth
     * - is_sudo
     * - is_hirer
     * - password
     * - cpf
     * - cnpj
     * O intuito das funções é apenas checar os
     * dados para persistir no banco de dados e
     * não garantir a veracidade dos mesmos
     */
    function validateUniqueNick($unq_nick){
        if (!is_string($unq_nick) or $unq_nick == null) {
            throw new ApiException('invalid field: unq_nick', 400);
        } else {
            $user = User::query()->where('unq_nick', $unq_nick)->get();
            if ($user->isNotEmpty()){
                throw new ApiException("invalid value: unq_nick $unq_nick already exists", 400);
            }
        }
    }

    function validateEmail($email){
        if (!is_string($email) or $email == null) {
            throw new ApiException('invalid field: email', 400);
        } else {
            $user = User::query()->where('email', $email)->get();
            if ($user->isNotEmpty()){
                throw new ApiException("invalid value: email $email already exists", 400);
            }
        }
    }

    function validateUserType($userType){
        if (!is_string($userType) or $userType == null) {
            throw new ApiException('invalid field: usr_type', 400);
        }
        if ($userType !== "PESSOA_FISICA" and $userType !== "PESSOA_JURIDICA") {
            throw new ApiException('invalid usr_type value: try use \'PESSOA_FISICA\'
                                        or \'PESSOA_JURIDICA\'', 400);
        }
    }

    function validateDtBirth($dtBirth){
        if (!is_string($dtBirth) or $dtBirth == null) {
            throw new ApiException('invalid field: dt_birth', 400);
        }
        if (!\DateTime::createFromFormat('Y-m-d', $dtBirth)){
            throw new ApiException('invalid dt_birth format: try use Y-m-d', 400);
        }
    }

    function validateIsSudo($isSudo){
        if (!is_bool($isSudo) or $isSudo === null) {
            throw new ApiException('invalid field: is_sudo', 400);
        }
    }

    function validateIsHirer($isHirer){
        if (!is_bool($isHirer) or $isHirer === null) {
            throw new ApiException('invalid field: is_hirer', 400);
        }
    }

    function validatePassword($password){
        if (!is_string($password) or $password == null) {
            throw new ApiException('invalid field: password', 400);
        }
    }

    function validateCpf($cpf){
        if (!is_string($cpf) or $cpf == null) {
            throw new ApiException('invalid field: cpf', 400);
        } else {
            $user = User::query()->where('cpf', $cpf)->get();
            if ($user->isNotEmpty()){
                throw new ApiException("invalid value: cpf $cpf already exists", 400);
            }
        }
    }

    function validateCnpj($cnpj){
        if (!is_string($cnpj) or $cnpj == null) {
            throw new ApiException('invalid field: cnpj', 400);
        } else {
            $user = User::query()->where('cnpj', $cnpj)->get();
            if ($user->isNotEmpty()){
                throw new ApiException("invalid value: cpnj $cnpj already exists", 400);
            }
        }
    }
}
