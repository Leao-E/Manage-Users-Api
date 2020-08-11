<?php

namespace App\Traits\Controllers\UserController;

use App\Exceptions\CustomExceptions\ApiException;
use App\Models\User;
use App\Traits\Controllers\CommonValidators;
use Illuminate\Http\Request;

trait UserBroker {

    use UserValidators, CommonValidators;

    function create (Request $request){
        $user = new User();

        if (!$request->has('unq_nick')) {
            throw new ApiException('missing field: unq_nick', 400);
        } else {
            $this->validateUniqueNick($request->unq_nick);
        }

        $user->unq_nick = $request->unq_nick;

        if (!$request->has('email')) {
            throw new ApiException('missing field: email', 400);
        } else {
            $this->validateEmail($request->email);
        }

        $user->email = $request->email;

        if (!$request->has('usr_type')) {
            throw new ApiException('missing field: usr_type', 400);
        } else {
            $this->validateUserType($request->usr_type);
        }

        $user->usr_type = $request->usr_type;

        if (!$request->has('name')) {
            throw new ApiException('missing field: name', 400);
        } else {
            $this->validateName($request->name);
        }

        $user->name = $request->name;

        if (!$request->has('dt_birth')) {
            throw new ApiException('missing field: dt_birth', 400);
        } else {
            $this->validateDtBirth($request->dt_birth);
        }

        $user->dt_birth = $request->dt_birth;

        if ($request->has('is_sudo')) {
            $this->validateIsSudo($request->is_sudo);
            $user->is_sudo = $request->is_sudo;
        }

        if ($request->has('is_hirer')) {
            $this->validateIsHirer($request->is_hirer);
            $user->is_hirer = $request->is_hirer;
        }

        if (!$request->has('password')) {
            throw new ApiException('missing field: password', 400);
        } else {
            $this->validatePassword($request->password);
        }

        $user->password = $request->password;

        if ($request->usr_type === 'PESSOA_FISICA'){
            if (!$request->has('cpf')) {
                throw new ApiException('missing field: cpf', 400);
            } else {
                $this->validateCpf($request->cpf);
            }

            $user->cpf = $request->cpf;

            if ($request->has('cnpj')) {
                $this->validateCnpj($request->cnpj);
                $user->cnpj = $request->cnpj;
            }
        }
        if ($request->usr_type === 'PESSOA_JURIDICA'){
            if (!$request->has('cnpj')) {
                throw new ApiException('missing field: cnpj', 400);
            } else {
                $this->validateCnpj($request->cnpj);
            }

            $user->cnpj = $request->cnpj;

            if ($request->has('cpf')){
                $this->validateCpf($request->cpf);
                $user->cpf = $request->cpf;
            }
        }

        return $user;
    }

    function update (User &$user, Request $request){

        if ($request->has('unq_nick') and $user->unq_nick != $request->unq_nick){
            $this->validateUniqueNick($request->unq_nick);
            $user->unq_nick = $request->unq_nick;
        }
        if ($request->has('email') and $user->email != $request->email){
            $this->validateEmail($request->email);
            $user->email = $request->email;
        }

        /*
         * Entra caso ocorra uma mudança no tipo de usuário
         */
        if ($request->has('usr_type')){
            $this->validateUserType($request->usr_type);

            /*
            * usr_type já foi validado, então é possivel
            * deduzir que se não for uma pessoa fisica
            * será uma pessoa juridica
            */

            if ($user->usr_type != $request->usr_type){
                $user->usr_type = $request->usr_type;
                if ($request->usr_type == 'PESSOA_FISICA'){
                    if ($request->has('cpf') and $user->cpf != $request->cpf){
                        $this->validateCpf($request->cpf);
                        $user->cpf = $request->cpf;
                    }elseif($user->cpf == null){
                        throw new ApiException('missing field: cpf', 400);
                    }
                    if ($request->has('cnpj') and $user->cnpj != $request->cnpj){
                        $this->validateCnpj($request->cnpj);
                        $user->cnpj = $request->cnpj;
                    }
                }else{
                    if ($request->has('cnpj') and $user->cpnj != $request->cpnj){
                        $this->validateCnpj($request->cnpj);
                        $user->cnpj = $request->cnpj;
                    }elseif($user->cnpj == null){
                        throw new ApiException('missing field: cnpj', 400);
                    }
                    if ($request->has('cpf') and $user->cpf != $request->cpf){
                        $this->validateCpf($request->cpf);
                        $user->cpf = $request->cpf;
                    }
                }
            }
        }else{
            /*
             * caso não ocorra mudança no usr_type
             */
            if ($request->has('cpf') and $user->cpf != $request->cpf){
                $this->validateCpf($request->cpf);
                $user->cpf = $request->cpf;
            }
            if ($request->has('cnpj') and $user->cnpj != $request->cnpj){
                $this->validateCnpj($request->cnpj);
                $user->cnpj = $request->cnpj;
            }
        }
        if ($request->has('name') and $user->name != $request->name){
            $this->validateName($request->name);
            $user->name = $request->name;
        }
        if ($request->has('dt_birth')){
            $this->validateDtBirth($request->dt_birth);
            $dt_birth = \DateTime::createFromFormat('Y-m-d', $request->dt_birth);
            if ($user->dt_birth->format('Y-m-d') != $dt_birth->format('Y-m-d')){
                $user->dt_birth = $dt_birth;
            }
        }
        if ($request->has('is_sudo') and $user->is_sudo != $request->is_sudo){
            $this->validateIsSudo($request->is_sudo);
            $user->is_sudo = $request->is_sudo;
        }
        if ($request->has('is_hirer') and $user->is_hirer != $request->is_hirer){
            $this->validateIsHirer($request->is_hirer);
            $user->is_hirer = $request->is_hirer;
        }
        if ($request->has('password') and $request->password != null){
            $this->validatePassword($request->password);
            $user->password = $request->password;
        }
    }
}
