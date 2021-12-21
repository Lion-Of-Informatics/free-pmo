@extends('layouts.app')

@section('title', trans('customer.create'))

@section('content')
<h1 class="page-header">
    <div class="pull-right">
        {{ link_to_route('customers.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
    </div>
    {{ trans('customer.create') }}
</h1>

{!! Form::open(['route' => 'customers.store']) !!}
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <legend>{{ __('customer.detail') }}</legend>
                        {!! FormField::text('name', ['required' => true]) !!}
                        {!! FormField::textarea('notes') !!}
                        <label for="customer_has_users">User</label>
                        <select class="js-example-basic-multiple" style="width: 100%" name="customer_has_users[]" multiple="multiple">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <legend>{{ __('customer.contact') }}</legend>
                        {!! FormField::text('pic') !!}
                        <div class="row">
                            <div class="col-xs-7">{!! FormField::email('email') !!}</div>
                            <div class="col-xs-5">{!! FormField::text('phone') !!}</div>
                        </div>
                        {!! FormField::text('website') !!}
                        {!! FormField::textarea('address') !!}
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                {!! Form::submit(trans('customer.create'), ['class' => 'btn btn-success']) !!}
                {{ link_to_route('customers.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@endsection

@section('ext_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script>
@endsection