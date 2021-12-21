@extends('layouts.app')

@section('title', __('project.edit'))

@section('content')

@include('projects.partials.breadcrumb', ['title' => __('project.edit')])

<div class="row">
    <div class="col-md-7 col-md-offset-2">
        {!! Form::model($project, ['route' => ['projects.update', $project], 'method' => 'patch']) !!}
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">{{ $project->name }}</h3></div>
            <div class="panel-body">
                {!! FormField::text('name', ['label' => __('project.name')]) !!}
                <div class="row">
                    <div class="col-md-8">
                        {!! FormField::textarea('description', ['label' => __('project.description'),'rows' => 5]) !!}
                    </div>
                    <div class="col-md-4">
                        {!! FormField::price('proposal_value', ['label' => __('project.proposal_value'), 'currency' => Option::get('money_sign', 'Rp')]) !!}
                        {!! FormField::price('project_value', ['label' => __('project.project_value'), 'currency' => Option::get('money_sign', 'Rp')]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        {!! FormField::text('proposal_date', ['label' => __('project.proposal_date')]) !!}
                    </div>
                    <div class="col-md-3">
                        {!! FormField::text('start_date', ['label' => __('project.start_date')]) !!}
                    </div>
                    <div class="col-md-3">
                        {!! FormField::text('due_date', ['label' => __('project.due_date')]) !!}
                    </div>
                    <div class="col-md-3">
                        {!! FormField::text('end_date', ['label' => __('project.end_date')]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!! FormField::select('status_id', ProjectStatus::toArray(), ['label' => __('app.status')]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! FormField::select('customer_id', $customers, ['label' => __('project.customer')]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="project_owners" class="control-label">Project Owners</label>
                        <select class="js-example-basic-multiple" id="project_owners" style="width: 100%" name="project_owners[]" multiple="multiple">
                            @foreach ($customer_has_users as $customer_has_user)
                                <option value="{{ $customer_has_user->user->id }}" {{ $customer_has_user->user->hasProject($project->id) ? 'selected' : '' }}>{{ $customer_has_user->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="development_url" class="control-label">Staging URL</label>
                        <input type="url" name="development_url" id="development_url" class="form-control" value="{{ $project->development_url }}">
                    </div>
                    <div class="col-md-6">
                        <label for="production_url" class="control-label">Production URL</label>
                        <input type="url" name="production_url" id="production_url" class="form-control" value="{{ $project->production_url }}">
                    </div>
                </div>
            </div>

            <div class="panel-footer">
                {!! Form::submit(__('project.update'), ['class' =>'btn btn-primary']) !!}
                {!! link_to_route('projects.show', __('project.back_to_show'), [$project], ['class' => 'btn btn-default']) !!}
                @can('delete', $project)
                {!! link_to_route('projects.delete', __('app.delete'), [$project], ['class' =>'btn btn-danger pull-right']) !!}
                @endcan
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('ext_css')
    {!! Html::style(url('assets/css/plugins/jquery.datetimepicker.css')) !!}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('ext_js')
    {!! Html::script(url('assets/js/plugins/jquery.datetimepicker.js')) !!}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('script')
<script>
(function() {
    $('#proposal_date,#start_date,#due_date,#end_date').datetimepicker({
        timepicker:false,
        format:'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false
    });
})();
</script>
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
</script>
@endsection
