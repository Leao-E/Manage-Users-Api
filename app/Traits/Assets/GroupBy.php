<?php

namespace App\Traits\Assets;

use App\Exceptions\CustomExceptions\ApiException;
use Illuminate\Support\Collection;

trait GroupBy {
    function canGroup (array $queryParams){
        if (isset($queryParams['groupBy'])){
            return true;
        }
        return false;
    }

    function group(Collection &$collection, array $queryParams){
        if (!$this->canGroup($queryParams)){
            throw new ApiException(
                'you need to pass groupBy as a query param to group',
                400
            );
        }

        $collection = $collection->groupBy($queryParams['groupBy']);
    }
}
