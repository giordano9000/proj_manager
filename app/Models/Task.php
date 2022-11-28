<?php

namespace App\Models;

use App\Enums\ProjectSort;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\ItemNotFoundException;

class Task extends SearchableModel
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

    /**
     * @var string[]
     */
    public $attributes = [
        'status' => 'open',
        'slug' => '',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'slug',
        'assignee',
        'difficulty',
        'priority',
        'project_id'
    ];

    /**
     * @var array
     */
    public $hidden = [
        'created_at',
        'updated_at'
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
     * Set slug attribute
     *
     * @return mixed
     */
    public function setSlugAttribute()
    {
        $this->attributes['slug'] = $this->id . '-' . $this->title;
    }

    /**
     * Get project that contains this task
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo( Project::class );
    }

    /**
     * Search by id
     *
     * @param $id
     * @return mixed
     */
    public function searchById( $id )
    {

        $query = $this->query();

        $query->where( 'id', $id )
            ->orWhere( 'slug', $id );

        $result = $query->get();

        if ( $result->isEmpty() ) throw new HttpResponseException( response()->json( [ 'error' => 'Task not found.' ], 404 ) );

        return $result->first();

    }

    /**
     * Search by params
     *
     * @param array $params
     */
    public function search( array $params )
    {

        $query = $this->newQuery();

        $query = $this->addStatusStatement( $query, $params );
        $query = $this->addOrderByStatement( $query, $params );

        $query->paginate( $params[ 'perPage' ] );

        $result = $query->get();

        if ( $result->isEmpty() ) {

            throw new HttpResponseException( response()->json( [ 'message' => 'No results found.' ], 204 ) );

        }

        return $result;

    }

    /**
     * Add status conditions to query
     *
     * @param $query
     * @param $params
     * @return mixed
     */
    protected function addStatusStatement( $query, $params )
    {

        if ( !empty( $params[ 'withClosed' ] ) ) {

            $query->where( 'status', TaskStatus::OPEN )
                ->orWhere( 'status', TaskStatus::BLOCK )
                ->orWhere( 'status', TaskStatus::CLOSE );

        } else if ( !empty( $params[ 'onlyClosed' ] ) ) {

            $query->where( 'status', TaskStatus::CLOSE );

        } else {

            $query->where( 'status', TaskStatus::OPEN )
                ->orWhere( 'status', TaskStatus::BLOCK );

        }

        return $query;

    }

}
