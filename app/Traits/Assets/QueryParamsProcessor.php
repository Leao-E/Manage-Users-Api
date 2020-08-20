<?php

namespace App\Traits\Assets;

use App\Exceptions\CustomExceptions\ApiException;
use App\Models\QueryProcessable\QueryProcessable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Object_;

trait QueryParamsProcessor {

    use Search, Order, Paginate, GroupBy;

    /*
     * author: Emanuel F.G. Leão
     * resume: A função recebe o target (que
     * deve ser ou Model ou Relation) e os
     * queryParams. Ao logo da execução são
     * checados os queryParams, de acordo
     * com os queryParams os dados podem ser
     * buscados, ordenados e paginados.
     * A função retorna um Objeto anônimo.
     */

    function queryProcessor (QueryProcessable $target, array $queryParams){
        /** @var Collection $collection */
        $response = new Object_();
        //busca

        $query = $target->getQuery();
        $columns = $target->getColumns();

        if ($this->canSearch($queryParams)){
            try {
                $collection = $this->search($query, $columns, $queryParams);
            } catch (ApiException $queryParamException) {
                throw $queryParamException;
            }
        }else{
            try {
                $collection = $query->get();
            } catch (\Exception $exception) {
                throw new ApiException($exception->getMessage(), 500);
            }

        }
        //total
        $response->total = $collection->count();
        try {
            //ordenação
            if ($this->canOrder($queryParams)){
                $this->order($collection, $queryParams);
            }

            //agrupar
            if ($this->canGroup($queryParams)){
                $this->group($collection, $queryParams);
            }

            $response->data = $collection;
            //paginação
            if ($this->canPaginate($queryParams)){
                $response = $this->paginate($collection, $queryParams);
            }
        } catch (ApiException $e) {
            throw $e;
        }

        return $response;
    }
}
