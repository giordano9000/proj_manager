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
     */
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
     * Search by id
     *
     * @param $id
     * @return mixed
     */
    public function searchById( string $id ) : mixed
    {

        $query = $this->query();

        $query->where( 'id', $id )
            ->orWhere( 'slug', $id );

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
        $query->select( 'id', 'title', 'description', 'slug', 'assignee', 'difficulty', 'priority', 'status' );
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
