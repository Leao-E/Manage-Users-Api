<?php

namespace App\Traits\Controllers\SystemController;

use App\Exceptions\CustomExceptions\ApiException;
use App\Models\System;

trait SystemValidators {

    function validateStorageSize ($storageSize){
        if (!is_float($storageSize) or $storageSize == null){
            throw new ApiException('invalid field: storg_size', 400);
        }
    }

    function validateStoragePath ($storagePath){
        if (!is_string($storagePath) or $storagePath == null) {
            throw new ApiException('invalid field: storg_path', 400);
        } else {
            $system = System::query()->where('storg_path', $storagePath)->get();
            if ($system->isNotEmpty()){
                throw new ApiException("invalid value: storg_path $storagePath already exists", 400);
            }
        }
    }
}
