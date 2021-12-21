<?php

namespace App\Http\Controllers\Customers;

use App\Entities\Partners\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Customer $customer)
    {
        return view('customers.users', compact('customer'));
    }
}
