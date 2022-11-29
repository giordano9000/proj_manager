<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\IndexProjectRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    private $taskService;
    private $projectService;

    /**
     * TaskController constructor.
     */
    public function __construct()
    {

        $this->taskService = new TaskService();
        $this->projectService = new ProjectService();

    }

    /**
     * Search tasks of a projects
     *
     * @param IndexProjectRequest $request
     * @param $projectId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index( IndexProjectRequest $request, string $projectId )
    {

        $params = $request->validated();
        $this->projectService->isValid( $projectId );
        $projects = $this->taskService->search( $projectId, $params );

        return response()->json( $projects );

    }

    /**
     * Store new task in a project
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( StoreTaskRequest $request, string $projectId )
    {

        $params = $request->validated();
        $this->projectService->isModifiable( $projectId );
        $project = $this->taskService->store( $projectId, $params );

        return response()->json( $project );

    }

    /**
     * Get task info
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show( string $projectId, string $taskId )
    {

        $this->projectService->isValid( $projectId );
        $this->taskService->isValid( $taskId );

        $task = $this->taskService->searchById( $projectId, $taskId );

        return response()->json( $task );

    }

    /**
     * Update task
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( UpdateTaskRequest $request, string $projectId, string $taskId )
    {

        $params = $request->validated();
        $this->projectService->isModifiable( $projectId );
        $this->taskService->isValid( $taskId );

        $project = $this->taskService->update( $taskId, $params );

        return response()->json( $project );

    }

    /**
     * Update status of a task
     *
     * @param $id
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus( string $projectId, string $taskId, string $status )
    {

        $this->projectService->isModifiable( $projectId );
        $this->taskService->isValid( $taskId );

        if ( !in_array( $status, TaskStatus::getValues() ) ) {

            return response()->json( [ 'message' => 'Invalid status.' ], 400 );

        }

        $this->taskService->changeStatus( $taskId, $status );

        return response()->json( [ 'message' => 'Status updated successfully.' ], 204 );

    }

}
