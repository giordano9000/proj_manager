<?php

namespace App\Models;

use App\Enums\ProjectSort;
use App\Interfaces\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Wrap all commons functions used from query builder
 * for search scope
 *
 * Class SearchableModel
 * @package App\Models
 */
abstract class SearchableModel extends Model implements Searchable
{

    /**
     * Add sortBy based on params
     *
     * @param $query
     * @param $params
     * @return mixed
     */
    public function addOrderByStatement( Builder $query, array $params ): mixed
    {

        switch ( $params[ 'sortBy' ] ) {

            case ProjectSort::ALPHA_DESC:
                $query->orderByDesc( 'title' );
                break;

            case ProjectSort::ALPHA_ASC:
                $query->orderBy( 'title' );
                break;

            case ProjectSort::UPDATE:
                $query->orderBy( 'updated_at' );
                break;

            default:
                $query->orderBy( 'created_at' );
                break;
        }

        return $query;

    }

}
