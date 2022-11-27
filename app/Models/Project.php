<?php

namespace App\Models;

use App\Enums\ProjectSort;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\ItemNotFoundException;

class Project extends Model
{

    use HasFactory, Uuid;

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var bool
     */
    public $incrementing = false;

    protected $attributes = [
        'status' => 'aperto'
    ];

    protected $fillable = [
        'title',
        'description',
        'slug',
        'status'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string'
    ];

    /**
     * Retrieve all tasks of a project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany( Task::class );
    }

    /**
     * Set slug attribute
     *
     * @return mixed
     */
    public function setSlugAttribute()
    {
        $this->attributes['slug'] = $this->id . '-' . $this->title;
    }

    /**
     * Add open tasks statement to query builder
     *
     * @param $query
     * @return mixed
     */
    private function addOpenTaskStatement( $query )
    {
        return $query->withCount( [ 'tasks AS open_tasks' => function ( Builder $q ) {
            $q->where( 'status', 'open' );
        } ] );
    }

    /**
     * Add closed tasks statement to query builder
     *
     * @param $query
     * @return mixed
     */
    private function addClosedTaskStatement( $query )
    {
        return $query->withCount( [ 'tasks AS closed_tasks' => function ( Builder $q ) {
            $q->where( 'status', 'close' );
        } ] );
    }

    /**
     * Search project by id
     *
     * @param $id
     * @return mixed
     */
    public function searchById( $id )
    {

        $query = $this->query();

        $query->where( 'id', $id )
            ->orWhere( 'slug', $id );

        $query = $this->addOpenTaskStatement( $query );
        $query = $this->addClosedTaskStatement( $query );

        $result = $query->get();

        if ( $result->isEmpty() ) throw new HttpResponseException( response()->json( [ 'error' => 'No results found.' ], 205 ) );

        return $result;

    }

    /**
     * Search project by params
     *
     * @param array $params
     */
    public function search( array $params )
    {

        $query = $this->newQuery();

        // STATUS PARAM
        if ( !empty( $params[ 'withClosed' ] ) ) {

            $query->where( 'status', 'aperto' )
                ->orWhere( 'status', 'chiuso' );

        } else if ( !empty( $params[ 'onlyClosed' ] ) ) {

            $query->where( 'status', 'chiuso' );

        } else {

            $query->where( 'status', 'aperto' );

        }

        // ORDER BY
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

        // COUNTERS
        $query = $this->addOpenTaskStatement( $query );
        $query = $this->addClosedTaskStatement( $query );

        // PAGINATION
        $query->paginate( $params[ 'perPage' ] );

        $result = $query->get();

        if ( $result->isEmpty() ) throw new HttpResponseException( response()->json( [ 'error' => 'No results found.' ], 204 ) );

        return $result;

    }

}
