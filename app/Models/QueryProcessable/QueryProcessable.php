<?php

namespace App\Models\QueryProcessable;

use Illuminate\Database\Eloquent\Builder;

class QueryProcessable
{
    private $query;
    private $columns;

    public function __construct(Builder $query, array $columns)
    {
        $this->query = $query;
        $this->columns = $columns;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
