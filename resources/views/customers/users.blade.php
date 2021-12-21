@extends('layouts.customer')

@section('title', trans('customer.user'))

@section('content-customer')

<div class="panel panel-default">
    <table class="table table-condensed">
        <thead>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
        </thead>
        <tbody>
            @forelse($customer->customer_users as $key => $customer_user)
            <tr>
                <td>{{ 1 + $key }}</td>
                <td>{{ $customer_user->user->name }}</td>
                <td>{{ $customer_user->user->email }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Users not found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection