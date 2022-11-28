<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\IndexProjectRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    private $taskService;

    /**
     * TaskController constructor.
     */
    public function __construct()
    {

        $this->taskService = new TaskService();

    }

    /**
     * Search tasks of a projects
     *
     * @param IndexProjectRequest $request
     * @param $projectId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index( IndexProjectRequest $request, $projectId )
    {

        $params = $request->validated();

        $projects = $this->taskService->search( $projectId, $params );

        return response()->json( $projects );

    }

    /**
     * Store new task in a project
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( StoreTaskRequest $request, $projectId )
    {

        $params = $request->validated();

        $project = $this->taskService->store( $projectId, $params );

        return response()->json( $project );

    }

    /**
     * Get task info
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show( $projectId, $taskId )
    {

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
    public function update( UpdateTaskRequest $request, $projectId, $taskId )
    {

        $params = $request->validated();

        $project = $this->taskService->update( $projectId, $taskId, $params );

        return response()->json( $project );

    }

    /**
     * Update status of a task
     *
     * @param $id
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus( $projectId, $taskId, $status )
    {

        if ( !in_array( $status, TaskStatus::getValues() ) ) {

            return response()->json( [ 'message' => 'Invalid status.' ], 400 );

        }

        $this->taskService->changeStatus( $projectId, $taskId, $status );

        return response()->json( [ 'message' => 'Status updated successfully.' ], 204 );

    }

}
