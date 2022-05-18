<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\User;
use Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = auth()->user()->tasks()->paginate(10);
        return response()->json([
            'status' => true,
            'tasks'  => $tasks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        return response()->json([
            'task' => Task::create(
                [
                    'name' => $request->input('name'),
                    'user_id' => auth()->user()->id
                ]
            ),
            'status' => true,
            'message' => 'Task added'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => 'task not found'
            ], 404);
        }
        return response()->json(['task' => $task, 'status' => true, 'message' => 'Task fetched']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request,$id)
    {
        $tasl = Task::find($id);
        if (!$tasl) {
            return response()->json([
                'status' => false,
                'message' => 'tasl not found'
            ], 404);
        }
        abort_unless($tasl->user_id == auth()->user()->id,401,'you are unauthorized to edit this task.');
        $tasl->update([
            'name' => $request->input('name'),
        ]);
        return response()->json(['task' => $tasl, 'status' => true, 'message' => 'Task updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        logger($id);
        $task = Task::find($id);
        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => 'task not found'
            ], 404);
        }
        $task->delete();
        return response()->json(['status' => true, 'message' => 'Task deleted']);
    }
}
