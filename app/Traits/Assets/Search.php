<?php

namespace App\Traits\Assets;

use App\Exceptions\CustomExceptions\ApiException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

trait Search {
    /*
     * auhtor: Emanuel F.G. Leão
     * resume: A função, após receber um array
     * com os queryParams, checa se há a chave
     * search. Retorna apenas true ou false
     */
    function canSearch (array $queryParams){
        if (isset($queryParams['search'])){
            return true;
        }
        return false;
    }
    /*
     * author: Emanuel F.G. Leão
     * resume: A função recebe o searchable e os
     * queryParams. Se os queryParams estiverem
     * corretos, e o searchable for uma instância
     * de Model ou Relation, a função vai retornar
     * uma intância de Collection com os resultados
     * da busca.
     *
     * PS: A função deve ser colocada num bloco try catch.
     * É esperado um ApiException
     */
    function search (Builder $query, array $columns, array $queryParams){
        /** @var Builder $query */
        //checa os queryParams
        if (!$this->canSearch($queryParams)){
            throw new ApiException('missing paginate params. pass \'search\' param', 400);
        }

        $value = $queryParams['search'];
        //checa instância de searchble

        $last_index = count($columns);

        for ($index = 0; $index < $last_index; ++$index){
            if ($index == 0){
                $query =  $query->where($columns[$index], 'LIKE', '%'.$value.'%');
            }else{
                $query = $query->orWhere($columns[$index], 'LIKE', '%'.$value.'%');
            }
        }

        return $query->get();
    }
}
