<?php

namespace App\Traits\Assets;

use App\Exceptions\CustomExceptions\ApiException;
use \Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Object_;

trait Paginate {

    /*
     * author: Emanuel F.G Leão
     * resume: É esperado que essa função receba um array
     * correspondente aos query params do request, checando
     * se possui as chaves paginate e page.
     */
    function canPaginate(array $queryParams){
        if (isset($queryParams['paginate']) and isset($queryParams['page'])){
            return true;
        }
        return false;
    }

    /*
     * author: Emanuel F.G. Leão
     * resume: A função recebe uma instância de Collection e um array.
     * O array corresponde aos query params e o  Collection corresponde
     * ao resultado da query do model.
     * É recomendado usar a função num bloco try/catch devido ao
     * 'throw new Exception'
     */
    function paginate(Collection $collection, array $queryParams){

        if (!$this->canPaginate($queryParams)){
            throw new ApiException('missing paginate params. pass \'paginate\' and \'page\' and query params', 400);
        }

        $page = $queryParams ['page'];
        $perPage = $queryParams ['paginate'];

        $response = new Object_();
        $response->data = $collection->forPage($page, $perPage);
        $response->total = $collection->count();
        $response->total_pages = ceil($collection->count()/$perPage);
        if ($response->total_pages < 1){
            $response->total_pages = 1;
        }
        $response->actual_page = intval($page);

        if ($page > $response->total_pages){
            throw new ApiException('invalid page offset', 400);
        }

        return $response;
    }
}
