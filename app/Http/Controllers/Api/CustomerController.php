<?php

namespace App\Http\Controllers\Api;

use App\CustomerHasUser;
use App\Entities\Partners\Customer;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::where('is_active', 1)
            ->orderBy('name')
            ->pluck('name', 'id');

        return response()->json($customers);
    }

    /**
     * Get all users that connected by customer
     * 
     * @param int (customer_id)
     * 
     * @return array
     */
    public function getCustomerUsers($customer_id)
    {
        return response()->json([
            'message'   => 'success, data has been retrieved',
            'data'      => CustomerHasUser::join('users', 'customer_has_users.user_id', '=', 'users.id')
                                            ->where('customer_has_users.customer_id', $customer_id)            
                                            ->get()
        ], 200);
    }
}
