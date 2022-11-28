<?php

namespace App\Services;

use App\Enums\Status;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Exceptions\HttpResponseException;

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
    public function searchById( $projectId, $taskId )
    {

        $this->projectModel->searchById( $projectId );

        $task = $this->taskModel->searchById( $taskId )->first();

        return $task->only( 'id', 'title', 'description', 'slug', 'assignee', 'difficulty', 'priority', 'status' );

    }

    /**
     *  Search tasks of a projects
     *
     * @param $params
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function search( $projectId, $params )
    {
        $project = $this->projectModel->searchById( $projectId );

        if ( $project->isEmpty() ) {

            throw new HttpResponseException( response()->json( [ 'message' => 'Project not found.' ], 404 ) );

        }

        return $this->taskModel->search( $params );

    }

    /**
     * Store new task in a project
     *
     * @param $params
     * @return mixed
     */
    public function store( $projectId, $params )
    {

        $project = $this->validateProject( $projectId );

        $task = Task::create( [
            'title' => $params[ 'title' ],
            'description' => $params[ 'description' ],
            'assignee' => $params[ 'assignee' ],
            'difficulty' => $params[ 'difficulty' ],
            'priority' => $params[ 'priority' ],
            'project_id' => $project->id
        ] );

        $task->setSlugAttribute();
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
    public function update( $projectId, $taskId, $params )
    {

        $project = $this->validateProject( $projectId );
        $task = $this->taskModel->searchById( $taskId );

        $task->title = $params[ 'title' ];
        $task->description = $params[ 'description' ];
        $task->assignee = $params[ 'assignee' ];
        $task->difficulty = $params[ 'difficulty' ];
        $task->priority = $params[ 'priority' ];

        $task->setSlugAttribute();
        $task->save();

        return $task->only( 'id', 'title', 'description', 'slug', 'assignee', 'difficulty', 'priority', 'status' );

    }

    /**
     * Update the task status
     *
     * @param $projectId
     * @param $taskId
     * @param $status
     * @return bool
     */
    public function changeStatus( $projectId, $taskId, $newStatus )
    {

        $this->validateProject( $projectId );
        $task = $this->taskModel->searchById( $taskId );
        $task->update( [ 'status' => $newStatus ] );

    }

    /**
     * Check the project exists and could be modified.
     * Return the project
     *
     * @param $projectId
     * @return mixed
     */
    private function validateProject( $projectId )
    {

        $project = $this->projectModel->searchById( $projectId )->first();

        if ( $project->status === Status::CLOSE ) {

            throw new HttpResponseException( response()->json( [ 'message' => 'Project is closed.' ], 400 ) );

        }

        return $project;

    }

}
