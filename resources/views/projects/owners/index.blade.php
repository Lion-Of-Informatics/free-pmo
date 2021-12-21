@extends('layouts.app')

@section('title', __('project.files').' | '.$project->name)

@section('content')
@include('projects.partials.breadcrumb',['title' => __('project.files')])

<h1 class="page-header">
    {{ $project->name }} <small>{{ __('project.files') }}</small>
</h1>

@include('projects.partials.nav-tabs')

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default table-responsive">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('project.files') }}</h3>
            </div>
            <table class="table table-condensed table-striped">
                <thead>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                </thead>
                <tbody class="sort-files">
                    @forelse($owners as $key => $owner)
                    <tr id="{{ $owner->id }}">
                        <td>{{ 1 + $key }}</td>
                        <td>{{ $owner->name }}</td>
                        <td>{{ $owner->email }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center">Project Owner is empty</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection