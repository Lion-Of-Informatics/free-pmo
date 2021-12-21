@if (auth()->user()->hasRole('client'))
{{ Form::open(['route' => ['projects.comments.store', $project]]) }}
<div class="row">
    <div class="col-md-9">{!! FormField::textarea('body', ['required' => true, 'label' => false, 'placeholder' => __('comment.create_text')]) !!}</div>
    <div class="col-md-3">
        {{ Form::submit(__('comment.create'), ['class' => 'btn btn-success btn-block']) }}<br>
    </div>
</div>
{{ Form::close() }}
@else 
@can('comment-on', $project)
{{ Form::open(['route' => ['projects.comments.store', $project]]) }}
<div class="row">
    <div class="col-md-9">{!! FormField::textarea('body', ['required' => true, 'label' => false, 'placeholder' => __('comment.create_text')]) !!}</div>
    <div class="col-md-3">
        {{ Form::submit(__('comment.create'), ['class' => 'btn btn-success btn-block']) }}<br>
    </div>
</div>
{{ Form::close() }}
@endcan
@endif


@foreach($comments as $comment)
<div class="alert alert-warning">
    <legend style="font-size: 14px;margin-bottom: 10px;">
        <span class="label label-default pull-right">{{ $comment->time_display }}</span>
        <strong>{{ $comment->creator->name }}</strong>
    </legend>
    <div class="pull-right">
            {{ link_to_route('projects.comments.index', __('app.edit'), [$project, 'action' => 'comment-edit', 'comment_id' => $comment->id], ['id' => 'edit-comment-'.$comment->id, 'class' => 'small', 'title' => __('comment.edit')]) }}
            {!! FormField::delete(
                ['route' => ['projects.comments.destroy', $project, $comment], 'class' => ''],
                '&times;',
                ['class' => 'btn-link', 'id' => 'delete-comment-'.$comment->id],
                ['comment_id' => $comment->id, 'page' => request('page')]
            ) !!}
    </div>
    {!! nl2br($comment->body) !!}
</div>
@endforeach
