<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\IndexProjectRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use App\Services\ProjectService;
use Illuminate\Http\Exceptions\HttpResponseException;
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

        $project = $this->projectService->isValid( $projectId );

        if ( !$project ) {

            return response()->json( [ 'message' => 'Project not found.' ], 404 );

        }

        $tasks = $this->taskService->search( $project->id, $params );

        return response()->json( $tasks );

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

        if ( !$this->projectService->isModifiable( $projectId ) ) {

            return response()->json( [ 'message' => 'Project is closed.' ], 400 );

        }

        $task = $this->taskService->store( $projectId, $params );

        return response()->json( $task->only( 'id', 'title', 'description', 'assignee', 'difficulty', 'priority', 'slug' ) );

    }

    /**
     * Get task info
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show( string $projectId, string $taskId )
    {

        if ( !$this->projectService->isValid( $projectId ) ) {

            return response()->json( [ 'message' => 'Project not found.' ], 404 );

        }

        if ( !$this->taskService->isValid( $taskId, $projectId ) ) {

            return response()->json( [ 'message' => 'Task not found.' ], 404 );

        }

        $task = $this->taskService->searchById( $taskId );

        return response()->json( $task->only( 'id', 'title', 'description', 'slug', 'assignee', 'difficulty', 'priority', 'status' ) );

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

        if ( !$this->projectService->isModifiable( $projectId ) ) {

            return response()->json( [ 'message' => 'Project is closed.' ], 400 );

        }

        if ( !$this->taskService->isValid( $taskId, $projectId ) ) {

            return response()->json( [ 'message' => 'Task not found.' ], 404 );

        }

        $task = $this->taskService->update( $taskId, $params );

        return response()->json( $task->only( 'id', 'title', 'description', 'slug', 'assignee', 'difficulty', 'priority', 'status' ) );

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

        if ( !$this->projectService->isModifiable( $projectId ) ) {

            return response()->json( [ 'message' => 'Project is closed.' ], 400 );

        }

        if ( !$this->taskService->isValid( $taskId, $projectId ) ) {

            return response()->json( [ 'message' => 'Task not found.' ], 404 );

        }

        if ( !in_array( $status, TaskStatus::getValues() ) ) {

            return response()->json( [ 'message' => 'Invalid status.' ], 400 );

        }

        $this->taskService->changeStatus( $taskId, $status );

        return response()->json( [ 'message' => 'Status updated successfully.' ], 204 );

    }

}
