<?php

namespace App\Models;

use App\Enums\ProjectSort;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;

/**
 * Wrap all commons functions used from query builder
 * for search scope
 *
 * Class SearchableModel
 * @package App\Models
 */
abstract class SearchableModel extends Model
{

    /**
     * Add sortBy based on params
     *
     * @param $query
     * @param $params
     * @return mixed
     */
    protected function addOrderByStatement( $query, $params )
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
