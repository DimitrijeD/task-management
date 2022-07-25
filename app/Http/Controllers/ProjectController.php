<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    public function getProjects(Request $request)
    {
        return response()->json(
            Project::where(['user_id' => auth()->user()->id])
                ->with(['tasks'])
                ->get()
        );
    }

    public function update(UpdateProjectRequest $request)
    {
        if(!$project = Project::find($request->project_id)) 
            return response()->json(['error' => __("Project doesn't exist.")], 404);

        if($project->user_id != auth()->user()->id) 
            return response()->json(['error' => __("Unauthorized.")], 401);

        $project->fill($request->all());
        
        return $project->save()
            ? response()->json($project)
            : response()->json(['error' => __("We are unable to proccess this request at this time.")]);
    }

    public function store(CreateProjectRequest $request)
    {
        if(!$project = Project::create([
            'user_id' => auth()->user()->id,
            'name' => $request->name,
        ]))
            response()->json(['error'=> __("An error occured while creating your project.")]);

        $project->tasks = [];

        return response()->json($project);
    }

    public function delete(Request $request)
    {
        if(!$project = Project::find($request->id)) 
            return response()->json(['error' => __("Project doesn't exist.")], 404);

        if($project->user_id != auth()->user()->id) 
            return response()->json(['error' => __("Unauthorized.")], 401);

        return $project->delete()
            ? response()->json(['success' => __("Project has been successfully deleted.")])
            : response()->json(['error'=> __("An error occured while deleting project.")]);
    }
    

}

