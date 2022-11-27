<?php

namespace App\Http\Controllers;

use App\Enums\ProjectSort;
use App\Http\Requests\IndexProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    private $projectService;

    public function __construct()
    {

        $this->projectService = new ProjectService();

    }

    public function index( IndexProjectRequest $request )
    {

        $params = $request->validated();

        $projects = $this->projectService->search( $params );

        return response()->json( $projects );

    }

    public function store( StoreProjectRequest $request )
    {

        $params = $request->validated();

        $project = $this->projectService->store( $params );

        return response()->json( $project );

    }

    public function show( $id )
    {

        $project = $this->projectService->searchById( $id );

        return response()->json( $project );

    }

    public function update( UpdateProjectRequest $request, $id )
    {

        $params = $request->validated();

        $project = $this->projectService->update( $id, $params );

        return response()->json( $project );

    }

//    public function change_status( Request $request, $id )
//    {
//
//        $project = $this->projectService->update( $id );
//
//        return response()->json( $project );
//
//    }

    public function destroy( $id )
    {

        $project = Project::find( $id );
        $project->delete();

        return response()->json( [
            'status' => 'success',
            'message' => 'Project deleted successfully',
            'project' => $project,
        ] );

    }

}
