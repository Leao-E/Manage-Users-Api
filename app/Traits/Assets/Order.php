<?php

namespace App\Traits\Assets;

use App\Exceptions\CustomExceptions\ApiException;
use Illuminate\Database\Eloquent\Collection;

trait Order {
    /*
     * author: Emanuel F.G. Leão
     * resume: A função apenas checa se, dentro
     * do array com os query params, existe os
     * parametros orderBy e orderDescBy, de forma
     * que eles não coexistam simultaneamente
     */
    function canOrder (array $queryParams){
        if (isset($queryParams['orderBy']) xor isset($queryParams['orderDescBy'])){
            return true;
        }
        return false;
    }

    /*
     * author: Emanuel F.G. Leão
     * resume: A função deve receber uma instância
     * de Collection e um array contendo os queryParams.
     * Após checar e validar os queryParams a
     * função ordena a coleção.
     * PS: $collection é passado por parâmetro;
     * A função joga um ApiException
     */
    function order (Collection &$collection, array $queryParams){
        if (!$this->canOrder($queryParams)){
            throw new ApiException('you can only pass one order param at time', 400);
        }

        if (isset($queryParams['orderBy'])) {
            $columnName = $queryParams['orderBy'];
            $collection = $collection->sortBy($columnName);
        } else {
            $columnName = $queryParams['orderDescBy'];
            $collection = $collection->sortByDesc($columnName);
        }
    }
}
