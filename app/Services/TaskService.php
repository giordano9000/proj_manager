<?php

namespace App\Services;

use App\Enums\Status;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Exceptions\HttpResponseException;
use phpDocumentor\Reflection\Types\Mixed_;

class TaskService
{

    private $projectModel;
    private $taskModel;

    /**
     * TaskService constructor.
     */
    public function __construct()
    {
        $this->projectModel = new Project();
        $this->taskModel = new Task();
    }

    /**
     * Search task by project and taskId
     *
     * @param $params
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function searchById( string $projectId, string $taskId ): array
    {

        $this->projectModel->searchById( $projectId );

        $task = $this->taskModel->searchById( $taskId );

        return $task->only( 'id', 'title', 'description', 'slug', 'assignee', 'difficulty', 'priority', 'status' );

    }

    /**
     *  Search tasks of a projects
     *
     * @param $params
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function search( string $projectId, array $params )
    {

        $project = $this->projectModel->searchById( $projectId );
        return $this->taskModel->search( $project->id, $params );

    }

    /**
     * Store new task in a project
     *
     * @param $params
     * @return mixed
     */
    public function store( string $projectId, array $params ): mixed
    {

        $task = Task::create( [
            'title' => $params[ 'title' ],
            'description' => $params[ 'description' ],
            'assignee' => $params[ 'assignee' ],
            'difficulty' => $params[ 'difficulty' ],
            'priority' => $params[ 'priority' ],
            'project_id' => $projectId
        ] );

        $task->save();

        return $task->only( 'id', 'title', 'description', 'assignee', 'difficulty', 'priority', 'slug' );

    }

    /**
     * Update task
     *
     * @param $projectId
     * @param $taskId
     * @param $params
     * @return mixed
     */
    public function update( string $taskId, array $params ): mixed
    {

        $task = $this->taskModel->searchById( $taskId );

        $task->title = $params[ 'title' ];
        $task->description = $params[ 'description' ];
        $task->assignee = $params[ 'assignee' ];
        $task->difficulty = $params[ 'difficulty' ];
        $task->priority = $params[ 'priority' ];

        $task->save();

        return $task->only( 'id', 'title', 'description', 'slug', 'assignee', 'difficulty', 'priority', 'status' );

    }

    /**
     * Update the task status
     *
     * @param string $projectId
     * @param string $taskId
     * @param string $newStatus
     */
    public function changeStatus( string $taskId, string $newStatus ): void
    {

        $task = $this->taskModel->searchById( $taskId );
        $task->update( [ 'status' => $newStatus ] );

    }

    /**
     * Check the task exists and return it
     *
     * @param string $taskId
     * @return mixed
     */
    public function isValid( string $taskId ): mixed
    {

        $task = $this->taskModel->searchById( $taskId );

        if ( empty( $task ) ) {

            throw new HttpResponseException( response()->json( [ 'message' => 'Task not found.' ], 404 ) );

        }

        return $task;

    }

}
