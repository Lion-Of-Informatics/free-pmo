@extends('layouts.app')

@section('title', trans('project.create'))

@section('content')
<ul class="breadcrumb hidden-print">
    <li>{{ link_to_route('projects.index',trans('project.projects')) }}</li>
    <li class="active">{{ trans('project.create') }}</li>
</ul>

<div class="row">
    <div class="col-md-4 col-md-offset-3">
        {!! Form::open(['route' => 'projects.store']) !!}
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">{{ trans('project.create') }}</h3></div>
            <div class="panel-body">
                {!! FormField::text('name', ['label' => trans('project.name')]) !!}
                {!! FormField::select('customer_id', $customers, ['placeholder' => __('customer.create')]) !!}
                <label for="project_owners" class="control-label">Project Owners</label>
                <select class="js-example-basic-multiple" id="project_owners" style="width: 100%" name="project_owners[]" multiple="multiple">
                </select>
                <div class="row">
                    <div class="col-md-6">
                        {!! FormField::text('proposal_date', ['label' => trans('project.proposal_date')]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! FormField::price('proposal_value', ['label' => trans('project.proposal_value'), 'currency' => Option::get('money_sign', 'Rp')]) !!}
                    </div>
                </div>
                {!! FormField::textarea('description', ['label' => trans('project.description')]) !!}
            </div>

            <div class="panel-footer">
                {!! Form::submit(trans('project.create'), ['class' => 'btn btn-primary']) !!}
                {!! link_to_route('projects.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) !!}
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}
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
        $('#proposal_date').datetimepicker({
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
    <script>
        /**
         * Dynamic select project owner when customer is changed
         * 
         */
        const customerSelectElement = document.getElementById('customer_id');
        
        customerSelectElement.addEventListener('change', () => {
            const selectedOptionElement         = customerSelectElement.options[customerSelectElement.selectedIndex];
            const customerElementCurrentValue   = selectedOptionElement.getAttribute('value');
            const selectProjectOwnersElement    = document.querySelector('.js-example-basic-multiple');

            selectProjectOwnersElement.innerHTML = '';
            $('.js-example-basic-multiple').select2('destroy');

            if( customerElementCurrentValue != '' ) {
                fetch(window.location.origin + `/api/v1/customers/${customerElementCurrentValue}/users`)
                    .then((res) => {
                        if( !res.ok ) {
                            res.text().then((response) => {
                                console.log(response);
                            })
                            return false;
                        } else {
                            return res.json();
                        }
                    })
                    .then((response) => {
                        if( response ) {
                            response.data.forEach((user) => {
                                selectProjectOwnersElement.innerHTML += `<option value="${user.user_id}">${user.name}</option>`;
                            });
                        }
                    });
            }
            $('.js-example-basic-multiple').select2();
        });
    </script>
@endsection
