<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    public function update(UpdateTaskRequest $request)
    {
        if(!$task = Task::with(['project'])->find($request->task_id)) 
            return response()->json(['error' => __("Task doesn't exist.")], 404);

        if($task->project->user_id != auth()->user()->id) 
            return response()->json(['error' => __("Unauthorized.")], 401);

        // Check if task is being migrated from one Project to another. Project must exist in order to execute migration
        if(isset($request->project_id) && $request->project_id != $task->project_id){
            if(!Project::find($request->project_id))
                return response()->json(['error' => __("You are trying to move Task to a Project which doesn't exist. Abort.")]);
        }

        $task->fill($request->all());

        return $task->save()
            ? response()->json($task)
            : response()->json(['error' => __("We are unable to proccess this request at this time.")]);
    }

    public function store(Request $request)
    {
        $props = [
            'project_id' => $request->project_id,
            'name' => $request->name,
            'description' => $request->description
        ];

        $props['priority'] = isset($request->priority)
            ? $request->priority
            : Task::MIN_PRIORITY;

        $task = Task::create($props);

        return $task 
            ? response()->json($task)
            : response()->json(['error'=> __("An error occured while creating a task.")]);
    }

    public function delete(Request $request)
    {
        $task = Task::with(['project'])->find($request->id);

        if(!$task) 
            return response()->json(['error' => __("Task doesn't exist.")], 404);

        if(isset($task->project) && $task->project->user_id != auth()->user()->id) 
            return response()->json(['error' => __("Unauthorized.")], 401);

        return $task->delete()
            ? response()->json(['success' => __("Task has been successfully deleted.")])
            : response()->json(['error' => __("An error occured while deleting task.")]);
    }
}
