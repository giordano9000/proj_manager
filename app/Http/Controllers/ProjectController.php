<?php

namespace App\Http\Controllers;

use App\Enums\ProjectSort;
use App\Enums\Status;
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

    /**
     * ProjectController constructor.
     */
    public function __construct()
    {

        $this->projectService = new ProjectService();

    }

    /**
     * Search and get projects informations
     *
     * @param IndexProjectRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index( IndexProjectRequest $request )
    {

        $params = $request->validated();

        $projects = $this->projectService->search( $params );

        return response()->json( $projects );

    }

    /**
     * Store new project
     *
     * @param StoreProjectRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( StoreProjectRequest $request )
    {

        $params = $request->validated();

        $project = $this->projectService->store( $params );

        return response()->json( $project );

    }

    /**
     * Get project info
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show( string $id )
    {

        $project = $this->projectService->searchById( $id );

        return response()->json( $project );

    }

    /**
     * Update title or description of a project
     *
     * @param UpdateProjectRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( UpdateProjectRequest $request, string $id )
    {

        $params = $request->validated();

        $project = $this->projectService->update( $id, $params );

        return response()->json( $project );

    }

    /**
     * Update status of a project
     *
     * @param $id
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus( string $id, string $status )
    {

        if ( !in_array( $status, Status::getValues() ) ) {

            return response()->json( [ 'message' => 'Invalid status.' ], 400 );

        }

        $response = response()->json( [ 'message' => 'Status updated successfully.' ], 204 );

        if ( !$this->projectService->changeStatus( $id, $status ) ) {

            $response = response()->json( [ 'message' => 'Operation not permitted.' ], 400 );

        }

        return $response;

    }

}
