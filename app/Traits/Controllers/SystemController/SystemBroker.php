<?php

namespace App\Traits\Controllers\SystemController;

use App\Exceptions\CustomExceptions\ApiException;
use App\Models\System;
use App\Traits\Controllers\CommonValidators;
use Illuminate\Http\Request;

trait SystemBroker {

    use SystemValidators, CommonValidators;

    function create (Request $request){
        $system = new System();

        if (!$request->has('name')){
            throw new ApiException('missing field: name', 400);
        } else {
            $this->validateName($request->name);
        }

        $system->name = $request->name;

        if (!$request->has('storg_size')){
            throw new ApiException('missing field: storg_size', 400);
        } else {
            $this->validateStorageSize($request->storg_size);
        }

        $system->storg_size = $request->storg_size;

        if (!$request->has('storg_path')){
            throw new ApiException('missing field: storg_path', 400);
        } else {
            $this->validateStoragePath($request->storg_path);
        }

        $system->storg_path = $request->storg_path;

        return $system;
    }

    function update (System &$system, Request $request){
        if ($request->has('name') and $system->name != $request->name){
            $this->validateName($request->name);
            $system->name = $request->name;
        }

        if ($request->has('storg_size') and $system->storg_size != $request->storg_size){
            $this->validateStorageSize($request->storg_size);
            $system->storg_size = $request->storg_size;
        }

        if ($request->has('storg_path') and $system->storg_path != $request->storg_path){
            $this->validateStoragePath($request->storg_path);
            $system->storg_path = $request->storg_path;
        }
    }
}
