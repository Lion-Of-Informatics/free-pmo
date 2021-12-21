<?php

namespace App\Http\Controllers\Projects;

use App\CustomerHasUser;
use App\Entities\Projects\Project;
use App\Entities\Projects\ProjectsRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\CreateRequest;
use App\Http\Requests\Projects\UpdateRequest;
use App\ProjectHasUser;
use Illuminate\Http\Request;
use ProjectStatus;

/**
 * Projects Controller.
 *
 * @author Nafies Luthfi <nafiesl@gmail.com>
 */
class ProjectsController extends Controller
{
    /**
     * Projects Repository class.
     *
     * @var \App\Entities\Projects\ProjectsRepository
     */
    private $repo;

    public function __construct(ProjectsRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * List of projects.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $status = null;
        $statusId = $request->get('status_id');
        if ($statusId) {
            $status = $this->repo->getStatusName($statusId);
        }

        if( !auth()->user()->hasRole('client') ) {
            $projects = $this->repo->getProjects($request->get('q'), $statusId, auth()->user());
        } else {
            $q = $request->get('q');
            $statusIds = array_keys(ProjectStatus::toArray());

            $projects = Project::select('projects.*')
                                        ->rightJoin('project_has_users', 'project_has_users.project_id', '=', 'projects.id')
                                        ->where('user_id', auth()->user()->id)
                                        ->where(function ($query) use ($q, $statusId, $statusIds) {
                                            $query->where('name', 'like', '%'.$q.'%');
                            
                                            if ($statusId && in_array($statusId, $statusIds)) {
                                                $query->where('status_id', $statusId);
                                            }
                                        })
                                        ->paginate(5);
        }

        return view('projects.index', compact('projects', 'status', 'statusId'));
    }

    /**
     * Show create project form.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->authorize('create', new Project());

        $customers = $this->repo->getCustomersList();

        return view('projects.create', compact('customers'));
    }

    /**
     * Create new project.
     *
     * @param  \App\Http\Requests\Projects\CreateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateRequest $request)
    {
        $this->authorize('create', new Project());

        $project = $this->repo->create($request->except('_token'));
        flash(trans('project.created'), 'success');

        return redirect()->route('projects.show', $project);
    }

    /**
     * Show project detail page.
     *
     * @param  \App\Entities\Projects\Project  $project
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Project $project)
    {
        if( !auth()->user()->hasRole('client')) {
            $this->authorize('view', $project);
        }
        return view('projects.show', compact('project'));
    }

    /**
     * Show project edit page.
     *
     * @param  \App\Entities\Projects\Project  $project
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $customers = $this->repo->getCustomersList();
        $customer_has_users = CustomerHasUser::where('customer_id', $project->customer_id)->get();

        return view('projects.edit', compact('project', 'customers', 'customer_has_users'));
    }

    /**
     * Update project data.
     *
     * @param  \App\Http\Requests\Projects\UpdateRequest  $request
     * @param  \App\Entities\Projects\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        $project->update([
            'name'  => $request->name,
            'description'   => $request->description,
            'proposal_date' => $request->proposal_date,
            'start_date'    => $request->start_date,
            'end_date'    => $request->end_date,
            'due_date'    => $request->due_date,
            'project_value' => $request->project_value,
            'proposal_value' => $request->proposal_value,
            'status_id' => $request->status_id,
            'customer_id'   => $request->customer_id,
            'development_url' => $request->development_url,
            'production_url' => $request->production_url
        ]);


        ProjectHasUser::where('project_id', $project->id)->delete();

        if( isset($request->project_owners) ) {
            foreach( $request->project_owners as $user_id ) {
                ProjectHasUser::create([
                    'project_id'    => $project->id,
                    'user_id'       => $user_id
                ]);
            }
        }

        flash(trans('project.updated'), 'success');

        return redirect()->route('projects.edit', $project);
    }

    /**
     * Show project deletion confirmation page.
     *
     * @param  \App\Entities\Projects\Project  $project
     * @return \Illuminate\Contracts\View\View
     */
    public function delete(Project $project)
    {
        $this->authorize('delete', $project);

        return view('projects.delete', compact('project'));
    }

    /**
     * Delete project record from the system.
     *
     * @param  \App\Entities\Projects\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        if ($project->id == request('project_id')) {
            $this->repo->delete($project->id);
            flash(trans('project.deleted'), 'success');
        } else {
            flash(trans('project.undeleted'), 'danger');
        }

        return redirect()->route('projects.index');
    }

    /**
     * Project subscription list page.
     *
     * @param  \App\Entities\Projects\Project  $project
     * @return \Illuminate\Contracts\View\View
     */
    public function subscriptions(Project $project)
    {
        if( !auth()->user()->hasRole('client') ) {
            $this->authorize('view-subscriptions', $project);
        }

        return view('projects.subscriptions', compact('project'));
    }

    /**
     * Project payment list page.
     *
     * @param  \App\Entities\Projects\Project  $project
     * @return \Illuminate\Contracts\View\View
     */
    public function payments(Project $project)
    {
        if( !auth()->user()->hasRole('client') ) {
            $this->authorize('view-payments', $project);
        }

        $project->load('payments.partner');

        return view('projects.payments', compact('project'));
    }

    /**
     * Update project status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entities\Projects\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function statusUpdate(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $project = $this->repo->updateStatus($request->get('status_id'), $project->id);
        flash(trans('project.updated'), 'success');

        return redirect()->route('projects.show', $project);
    }

    /**
     * Project jobs reorder action.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entities\Projects\Project  $project
     * @return string|null
     */
    public function jobsReorder(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        if ($request->expectsJson()) {
            $data = $this->repo->jobsReorder($request->get('postData'));

            return 'oke';
        }
    }
}
