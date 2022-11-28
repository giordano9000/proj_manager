<?php

namespace App\Services;

use App\Enums\Status;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

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
     *
     *
     * @param $params
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function searchById( $id )
    {
        return $this->projectModel->searchById( $id )->first();
    }

    /**
     *
     *
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

        $project = $this->projectModel->searchById( $id )->first();
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

        $project = $this->projectModel->searchById( $id )->first();

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

}
