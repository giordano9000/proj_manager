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
use DB;

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

    /**
     * @var array
     */
    protected $attributes = [
        'status' => Status::OPEN
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'slug'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'status'
    ];

    /**
     * @var string[]
     */
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
     * @return string
     */
    public function getSlugAttribute()
    {
        return $this->id . '-' . $this->title;
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
     * @param Builder $query
     * @return Builder
     */
    private function addOpenTaskCounter( Builder $query ): Builder
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
    private function addClosedTaskCounter( Builder $query )
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
    public function searchById( string $id ): mixed
    {

        $query = $this->query();

        $query->where( 'id', $id )
            ->orWhere( DB::raw("CONCAT(`id`, '-', `title`)"), 'LIKE', $id );

        $query = $this->addOpenTaskCounter( $query );
        $query = $this->addClosedTaskCounter( $query );

        return $query->first();

    }

    /**
     * Search by params
     *
     * @param array $params
     * @return mixed
     */
    public function search( array $params ): mixed
    {

        $query = $this->newQuery();

        $query = $this->addStatusStatement( $query, $params );
        $query = $this->addOrderByStatement( $query, $params );

        // COUNTERS
        $query = $this->addOpenTaskCounter( $query, $params );
        $query = $this->addClosedTaskCounter( $query, $params );

        $query->paginate( $params[ 'perPage' ] );

        return $query->get();

    }

    /**
     * Add status conditions to query
     *
     * @param $query
     * @param $params
     * @return mixed
     */
    public function addStatusStatement( Builder $query, array $params ): mixed
    {

        if ( !empty( $params[ 'withClosed' ] ) ) {

            $query->whereIn( 'status', Status::getValues() );

        } else if ( !empty( $params[ 'onlyClosed' ] ) ) {

            $query->where( 'status', Status::CLOSE );

        } else {

            $query->where( 'status', Status::OPEN );

        }

        return $query;

    }

}
