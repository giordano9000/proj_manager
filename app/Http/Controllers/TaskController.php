<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function __construct()
    {
        // service task
    }

    public function list()
    {

        $tasks = Task::all();

        return response()->json([
            'status' => 'success',
            'tasks' => $tasks,
        ]);

    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Task created successfully',
            'task' => $task,
        ]);

    }

    public function show($id)
    {

        $task = Task::find($id);

        return response()->json([
            'status' => 'success',
            'task' => $task,
        ]);

    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $task = Task::find($id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Task updated successfully',
            'task' => $task,
        ]);

    }

    public function destroy($id)
    {

        $task = Task::find($id);
        $task->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Task deleted successfully',
            'task' => $task,
        ]);

    }

}
