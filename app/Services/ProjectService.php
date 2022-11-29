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
            'description' => $params[ 'description' ]
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
    public function changeStatus( $id, $newStatus )
    {

        $project = $this->projectModel->searchById( $id );

        if ( $newStatus === Status::OPEN && $project->status === Status::OPEN ) {

            return true;

        }

        if ( $newStatus === Status::CLOSE && $project->unclosedTasks()->get()->isEmpty() ) {

            $project->update([
                'status' => Status::CLOSE
            ]);

            return true;

        }

        return false;

    }

    /**
     * Check the project exists and return it
     *
     * @param string $projectId
     * @return mixed
     */
    public function isValid( string $projectId ): mixed
    {

        $project = $this->projectModel->searchById( $projectId );

        if ( empty( $project ) ) {

            throw new HttpResponseException( response()->json( [ 'message' => 'Project not found.' ], 404 ) );

        }

        return $project;

    }

    /**
     * Check the project exists and could be modified.
     * Return the project
     *
     * @param $projectId
     * @return mixed
     */
    public function isModifiable( string $projectId ): mixed
    {

        $project = $this->isValid( $projectId );

        if ( $project->status === Status::CLOSE ) {

            throw new HttpResponseException( response()->json( [ 'message' => 'Project is closed.' ], 400 ) );

        }

        return $project;

    }

}
