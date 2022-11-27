<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use App\Enums\ProjectSort;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Support\ItemNotFoundException;

class ProjectService
{

    private $projectModel;

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
        return $this->projectModel->searchById( $id );
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
            'description' => $params[ 'description' ],
            'slug' => ''
        ] );

        $project->setSlugAttribute();
        $project->save();

        return $project;

    }

    public function update( $id, $params )
    {

            $project = $this->projectModel->searchById( $id )->first();
            $project->title = $params[ 'title' ];
            $project->description = $params[ 'description' ];
            $project->setSlugAttribute();
            $project->save();

            return $project;

    }

}
