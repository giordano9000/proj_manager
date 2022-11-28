<?php

namespace App\Interfaces;


use Illuminate\Database\Eloquent\Builder;

/**
 * Class SearchableModel
 * @package App\Models
 */
interface Searchable
{

    function addOrderByStatement( Builder $query, array $params );

}
