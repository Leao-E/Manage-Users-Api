<?php

namespace App\Exceptions\CustomExceptions;

use Exception;

/*
 * author: Emanuel F.G. Leão
 * resume: A exceção foi pensada pra ser usada
 * quando, caso ocorra o erro, isso influêncie
 * no status final da resposta para o client
 */

class ApiException extends Exception
{
    private $status;

    public function __construct($msg, $status)
    {
        $this->message = $msg;
        $this->status = $status;
    }

    public function getStatus (){
        return $this->status;
    }
}
