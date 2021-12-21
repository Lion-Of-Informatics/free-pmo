<?php

namespace App\Http\Controllers\Projects;

use App\Entities\Projects\Project;
use App\Http\Controllers\Controller;
use App\ProjectHasUser;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index(Project $project)
    {
        $owners = ProjectHasUser::select('users.id', 'users.name', 'users.email')
                                ->join('users', 'project_has_users.user_id', '=', 'users.id')
                                ->where('project_has_users.project_id', '=', $project->id)
                                ->get();
        return view('projects.owners.index', compact('project', 'owners'));
    }
}
