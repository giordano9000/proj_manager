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
    public function searchById( string $taskId ): Task
    {

        $task = $this->taskModel->searchById( $taskId );

        return $task;

    }

    /**
     *  Search tasks of a projects
     *
     * @param $params
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function search( string $projectId, array $params )
    {

        return $this->taskModel->search( $projectId, $params );

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
        $task->setSlugAttribute();
        $task->save();

        return $task;

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
        $task->setSlugAttribute();
        $task->save();

        return $task;

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
    public function isValid( string $taskId, string $projectId ): mixed
    {
//die($taskId . '    ' . $projectId);
        $task = $this->taskModel->searchById( $taskId, $projectId );
//die(print_r($task, 1));

        if ( !$task ) {

            return false;

        }

        return $task;

    }

}
