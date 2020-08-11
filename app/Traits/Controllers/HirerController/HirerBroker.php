<?php

namespace App\Traits\Controllers\HirerController;

use App\Exceptions\CustomExceptions\ApiException;
use App\Models\Hirer;
use App\Traits\Controllers\CommonValidators;
use Illuminate\Http\Request;

trait HirerBroker {

    use HirerValidators, CommonValidators;

    function create (Request $request) {
        $hirer = new Hirer();

        if (!$request->has('name')){
            throw new ApiException('missing field: name', 400);
        } else {
            $this->validateName($request->name);
        }

        $hirer->name = $request->name;

        if (!$request->has('user_id')){
            throw new ApiException('missing field: user_id', 400);
        } else {
            $user = $this->validateUserId($request->user_id);
        }

        $hirer->user_id = $user->id;

        if (!$request->has('hirer_type')){
            throw new ApiException('missing field: hirer_typer', 400);
        } else {
            $this->validateHirerType($request->hirer_type);
        }

        $hirer->hirer_type = $request->hirer_type;

        if ($request->hirer_type === 'ORGAO_PUBLICO' or $request->hirer_type === 'PESSOA_JURIDICA'){
            if (!$request->has('cnpj')){
                throw new ApiException('missing field: cnpj', 400);
            } else {
                $this->validateHirerType($request->hirer_type);
            }

            $hirer->cnpj = $request->cnpj;

        }else{

            $hirer->cnpj = null;

        }

        return $hirer;
    }


    /*
     * name
     * user_id
     * cnpj
     * hirer_type
     */
    function update(Hirer &$hirer, Request $request) {
        if ($request->has('name') and $hirer->name != $request->name) {
            $this->validateName($request->name);
            $hirer->name = $request->name;
        }

        if ($request->has('user_id') and $hirer->user_id != $request->user_id) {
            $user = $this->validateUserId($request->user_id);
            $hirer->user_id = $user->id;
        }

        if ($request->has('hirer_type') and $hirer->hirer_type != $request->hirer_type) {
            $this->validateHirerType($request->hirer_type);
            //pessoa fisica para orgão publico ou pessoa juridica
            if ($request->hirer_type === 'ORGAO_PUBLICO' or $request->hirer_type === 'PESSOA_JURIDICA') {
                if ($hirer->cnpj === null){
                    if ($request->has('cnpj')) {
                        $this->validateCnpj($request->cnpj);
                        $hirer->cnpj = $request->cnpj;
                    }else {
                        throw new ApiException('missing field: cnpj', 400);
                    }
                }else {
                    if ($request->has('cnpj') and $hirer->cnpj != $request->cnpj) {
                        $this->validateCnpj($request->cnpj);
                        $hirer->cnpj = $request->cnpj;
                    }
                }
            }else{
                $hirer->cnpj = null;
            }
        }else{
            if ($hirer->hirer_type === 'PESSOA_FISICA') {
                $hirer->cnpj = null;
            }elseif ($request->has('cnpj') and $hirer->cnpj != $request->cnpj) {
                $this->validateCnpj($request->cnpj);
                $hirer->cnpj = $request->cnpj;
            }
        }
    }
}
