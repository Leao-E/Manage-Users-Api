<?php

namespace App\Traits\Assets;

use App\Exceptions\CustomExceptions\ApiException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Object_;

trait QueryParamsProcessor {

    use Search, Order, Paginate;

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

    function queryProcessor ($target, array $queryParams){
        if (!$target instanceof Model and !$target instanceof Relation){
            throw new ApiException('invalid target', 500);
        }

        $response = new Object_();
        //busca
        if ($this->canSearch($queryParams)){
            try {
                $response->data = $this->search($target, $queryParams);
            } catch (ApiException $queryParamException) {
                throw $queryParamException;
            }
        }else{
            try {
                $response->data = $target->get();
            } catch (\Exception $exception) {
                throw new ApiException($exception->getMessage(), 500);
            }

        }
        //total
        $response->total = $response->data->count();
        //ordenação
        if ($this->canOrder($queryParams)){
            try {
                $this->order($response->data, $queryParams);
            } catch (ApiException $queryParamException) {
                throw $queryParamException;
            }
        }
        //paginação
        if ($this->canPaginate($queryParams)){
            try {
                $response = $this->paginate($response->data, $queryParams);
            } catch (ApiException $queryParamException) {
                throw $queryParamException;
            }
        }

        return $response;
    }
}
