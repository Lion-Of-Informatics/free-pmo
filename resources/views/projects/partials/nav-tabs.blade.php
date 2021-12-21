<!-- Nav tabs -->
<ul class="nav nav-tabs">
    <li class="{{ Request::segment(3) == null ? 'active' : '' }}">
        {!! link_to_route('projects.show', __('project.detail'), $project) !!}
    </li>
    <li class="{{ Request::segment(3) == 'activities' ? 'active' : '' }}">
        {!! link_to_route('projects.activities.index', __('project.activities'), $project) !!}
    </li>
    
    @if (auth()->user()->hasRole('client'))
        <li class="{{ Request::segment(3) == 'jobs' ? 'active' : '' }}">
            {!! link_to_route('projects.jobs.index', __('project.jobs').' ('.$project->jobs->count().')', $project) !!}
        </li>
    @else 
        @can('view-jobs', $project)
            <li class="{{ Request::segment(3) == 'jobs' ? 'active' : '' }}">
                {!! link_to_route('projects.jobs.index', __('project.jobs').' ('.$project->jobs->count().')', $project) !!}
            </li>
        @endcan
    @endif


    @if (!auth()->user()->hasRole('client'))
        <li class="{{ Request::segment(3) == 'issues' ? 'active' : '' }}">
            {!! link_to_route('projects.issues.index', __('project.issues').' ('.$project->issues->count().')', $project) !!}
        </li>
    @endif

    @if (auth()->user()->hasRole('client'))
        <li class="{{ Request::segment(3) == 'comments' ? 'active' : '' }}">
            {!! link_to_route('projects.comments.index', __('comment.list').' ('.$project->comments->count().')', $project) !!}
        </li>
    @else 
        @can('view-comments', $project)
            <li class="{{ Request::segment(3) == 'comments' ? 'active' : '' }}">
                {!! link_to_route('projects.comments.index', __('comment.list').' ('.$project->comments->count().')', $project) !!}
            </li>
        @endcan
    @endif

    @if (auth()->user()->hasRole('client'))
        <li class="{{ Request::segment(3) == 'payments' ? 'active' : '' }}">
            {!! link_to_route('projects.payments', __('project.payments').' ('.$project->payments->count().')', $project) !!}
        </li>
    @else 
        @can('view-payments', $project)
            <li class="{{ Request::segment(3) == 'payments' ? 'active' : '' }}">
                {!! link_to_route('projects.payments', __('project.payments').' ('.$project->payments->count().')', $project) !!}
            </li>
        @endcan
    @endif

    @if (auth()->user()->hasRole('client'))
        <li class="{{ Request::segment(3) == 'subscriptions' ? 'active' : '' }}">
            {!! link_to_route('projects.subscriptions', __('project.subscriptions').' ('.$project->subscriptions->count().')', $project) !!}
        </li>
    @else 
        @can('view-subscriptions', $project)
            <li class="{{ Request::segment(3) == 'subscriptions' ? 'active' : '' }}">
                {!! link_to_route('projects.subscriptions', __('project.subscriptions').' ('.$project->subscriptions->count().')', $project) !!}
            </li>
        @endcan
    @endif

    @can('view-invoices', $project)
    <li class="{{ Request::segment(3) == 'invoices' ? 'active' : '' }}">
        {!! link_to_route('projects.invoices', __('project.invoices').' ('.$project->invoices->count().')', $project) !!}
    </li>
    @endcan
    
    @if (auth()->user()->hasRole('client'))
        <li class="{{ Request::segment(3) == 'files' ? 'active' : '' }}">
            {!! link_to_route('projects.files', __('project.files').' ('.$project->files->count().')', $project) !!}
        </li>
    @else
        @can('view-files', $project)
            <li class="{{ Request::segment(3) == 'files' ? 'active' : '' }}">
                {!! link_to_route('projects.files', __('project.files').' ('.$project->files->count().')', $project) !!}
            </li>
        @endcan
    @endif

    <li class="{{ Request::segment(3) == 'owners' ? 'active' : '' }}">
        {!! link_to_route('projects.owners', __('project.owners').' ('.$project->project_has_owners->count().')', $project) !!}
    </li>
</ul>
<br>
