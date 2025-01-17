<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">{{ trans('project.detail') }}</h3></div>
    <div class="panel-body">
        <table class="table table-condensed">
            <tbody>
                <tr><td class="col-xs-3">{{ trans('project.name') }}</td><td class="col-xs-9">{{ $project->name }}</td></tr>
                <tr><td>{{ trans('project.description') }}</td><td>{!! nl2br($project->description) !!}</td></tr>
                <tr><td>{{ trans('project.proposal_date') }}</td><td>{{ date_id($project->proposal_date) }}</td></tr>
                @can('see-pricings', $project)
                <tr><td>{{ trans('project.proposal_value') }}</td><td class="text-right">{{ format_money($project->proposal_value) }}</td></tr>
                <tr><td>{{ trans('project.project_value') }}</td><td class="text-right">{{ format_money($project->project_value) }}</td></tr>
                @endcan
                <tr><td>{{ trans('project.start_date') }}</td><td>{{ date_id($project->start_date) }}</td></tr>
                <tr><td>{{ trans('project.end_date') }}</td><td>{{ date_id($project->end_date) }}</td></tr>
                <tr><td>{{ trans('project.due_date') }}</td><td>{{ date_id($project->due_date) }}</td></tr>
                <tr><td>{{ trans('app.status') }}</td><td>{{ $project->present()->status }}</td></tr>
                <tr>
                    <td>{{ trans('project.customer') }}</td>
                    <td>
                        {{ $project->customer->nameLink() }}
                    </td>
                </tr>
                <tr>
                    <td>Owner</td>
                    <td>
                        @foreach ($project->project_has_owners as $key => $project_owner)
                            <a href="#">{{ $project_owner->user->name }}{{ $key != count($project->project_has_owners) - 1 ? ', ' : '' }}</a>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td>Development URL</td>
                    <td><a href="{{ $project->development_url }}">{{ $project->development_url }}</a></td>
                </tr>
                <tr>
                    <td>Production URL</td>
                    <td><a href="{{ $project->production_url }}">{{ $project->production_url }}</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
