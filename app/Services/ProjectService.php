<?php

namespace App\Services;

use App\Enums\Status;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProjectService
{

    private $projectModel;

    /**
     * ProjectService constructor.
     */
    public function __construct()
    {
        $this->projectModel = new Project();
    }

    /**
     * @param $params
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function searchById( $id )
    {
        return $this->projectModel->searchById( $id );
    }

    /**
     * @param $params
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function search( $params )
    {
        return $this->projectModel->search( $params );
    }

    /**
     * Store new project
     *
     * @param $params
     * @return mixed
     */
    public function store( $params )
    {

        $project = Project::create( [
            'title' => $params[ 'title' ],
            'description' => $params[ 'description' ],
        ] );

        $project->setSlugAttribute();
        $project->save();

        return $project;

    }

    /**
     * Update title or description
     *
     * @param $id
     * @param $params
     * @return mixed
     */
    public function update( $id, $params )
    {

        $project = $this->projectModel->searchById( $id );
        $project->title = $params[ 'title' ];
        $project->description = $params[ 'description' ];
        $project->setSlugAttribute();
        $project->save();

        return $project;

    }

    /**
     * Update status
     *
     * @param $id
     * @param $newStatus
     * @return bool
     */
    public function changeStatus( $id, $newStatus ): bool
    {

        $project = $this->projectModel->searchById( $id );

        if ( $newStatus == $project->status ) {

            return true;

        }

        if ( $newStatus == Status::CLOSE && $project->unclosedTasks()->get()->isEmpty() ) {

            $project->update([
                'status' => Status::CLOSE
            ]);

            return true;

        }

        return false;

    }

    /**
     * Check the project exists
     *
     * @param string $projectId
     * @return mixed
     */
    public function isValid( string $projectId ): mixed
    {

        $project = $this->projectModel->searchById( $projectId );

        if ( !$project ) {

            return false;

        }

        return $project;

    }

    /**
     * Check the project exists and can be modified.
     *
     * @param $projectId
     * @return mixed
     */
    public function isModifiable( string $projectId ): mixed
    {

        $project = $this->isValid( $projectId );

        if ( $project && $project->status === Status::CLOSE ) {

            return false;

        }

        return $project;

    }

}
