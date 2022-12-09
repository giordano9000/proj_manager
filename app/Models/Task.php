<?php

namespace App\Models;

use App\Enums\ProjectSort;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\ItemNotFoundException;
use DB;

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
        'status' => 'open'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'assignee',
        'difficulty',
        'priority',
        'project_id',
        'slug'
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

    public function setSlugAttribute() : void
    {
        $this->attributes[ 'slug' ] = $this->id . '-' . $this->title;
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
     * Search by id, with optional projectId filter
     *
     * @param string $taskId
     * @param string|null $projectId
     * @return mixed
     */
    public function searchById( string $taskId, string $projectId = NULL ) : mixed
    {

        $query = $this->query();

        if ( $projectId ) {

            $query->where( function ( Builder $query ) use ( $projectId ) {

                return $query->whereHas( 'project', function ( $builder ) use ( $projectId ) {
                    $builder->where( 'id', $projectId )
                        ->orWhere( 'slug', $projectId );
                } );

            } );

        }

        $query->where( function ( Builder $query ) use ( $taskId ) {

            return $query->where( 'id', $taskId )
                ->orWhere( 'slug', $taskId );

        } );

        return $query->first();

    }

    /**
     * Search by params
     *
     * @param string $projectId
     * @param array $params
     * @return array|Builder[]|Collection
     */
    public function search( string $projectId, array $params )
    {

        $query = $this->newQuery();
        $query->select( 'id', 'title', 'description', 'assignee', 'difficulty', 'priority', 'status', 'slug' );
        $query->where( 'project_id', $projectId );

        $query = $this->addStatusStatement( $query, $params );
        $query = $this->addOrderByStatement( $query, $params );

        $query->paginate( $params[ 'perPage' ] );

        return $query->get();

    }

    /**
     * Add status conditions to query
     *
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function addStatusStatement( Builder $query, array $params ) : Builder
    {

        if ( !empty( $params[ 'withClosed' ] ) ) {

            $query->whereIn( 'status', TaskStatus::getValues() );

        } else if ( !empty( $params[ 'onlyClosed' ] ) ) {

            $query->where( 'status', TaskStatus::CLOSE );

        } else {

            $query->whereIn( 'status', [ TaskStatus::OPEN, TaskStatus::BLOCK ] );

        }

        return $query;

    }

}
