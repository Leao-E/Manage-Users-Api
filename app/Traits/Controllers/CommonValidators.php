<?php

namespace App\Traits\Controllers;

use App\Exceptions\CustomExceptions\ApiException;

trait CommonValidators {

    function validateName($name){
        if (!is_string($name) or $name == null) {
            throw new ApiException('invalid field: name', 400);
        }
    }

}
