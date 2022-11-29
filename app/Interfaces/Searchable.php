<?php

namespace App\Interfaces;


use Illuminate\Database\Eloquent\Builder;

/**
 * Class SearchableModel
 * @package App\Models
 */
interface Searchable
{

    function searchById( string $id ): mixed;
    function addStatusStatement( Builder $query, array $params ): mixed;
    function addOrderByStatement( Builder $query, array $params ): mixed;

}
