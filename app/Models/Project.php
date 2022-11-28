<?php

namespace App\Models;

use App\Enums\ProjectSort;
use App\Enums\Status;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\ItemNotFoundException;

class Project extends SearchableModel
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
        'slug' => '',
        'status' => Status::OPEN
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
     * Set slug attribute
     *
     * @return mixed
     */
    public function setSlugAttribute()
    {
        $this->attributes['slug'] = $this->id . '-' . $this->title;
    }

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
     * Retrieve all unclosed tasks of a project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unclosedTasks()
    {
        return $this->hasMany( Task::class )->whereIn('status', [ TaskStatus::OPEN, TaskStatus::BLOCK ] );
    }

    /**
     * Retrieve all unclosed tasks of a project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function closedTasks()
    {
        return $this->hasMany( Task::class )->where('status', TaskStatus::CLOSE );
    }

    /**
     * Add open tasks statement to query builder
     *
     * @param $query
     * @return mixed
     */
    private function addOpenTaskCounter( $query )
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
    private function addClosedTaskCounter( $query )
    {
        return $query->withCount( [ 'tasks AS closed_tasks' => function ( Builder $q ) {
            $q->where( 'status', 'close' );
        } ] );
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

        $query = $this->addOpenTaskCounter( $query );
        $query = $this->addClosedTaskCounter( $query );

        $result = $query->get();

        if ( $result->isEmpty() ) throw new HttpResponseException( response()->json( [ 'error' => 'Project not found.' ], 404 ) );

        return $result;

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

        // COUNTERS
        $query = $this->addOpenTaskCounter( $query, $params );
        $query = $this->addClosedTaskCounter( $query, $params );

        $query->paginate( $params[ 'perPage' ] );

        $result = $query->get();

        if ( $result->isEmpty() ) {

            throw new HttpResponseException( response()->json( [ 'error' => 'No results found.' ], 204 ) );

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

            $query->where( 'status', Status::OPEN )
                ->orWhere( 'status', Status::CLOSE );

        } else if ( !empty( $params[ 'onlyClosed' ] ) ) {

            $query->where( 'status', Status::CLOSE );

        } else {

            $query->where( 'status', Status::OPEN );

        }

        return $query;

    }

}
