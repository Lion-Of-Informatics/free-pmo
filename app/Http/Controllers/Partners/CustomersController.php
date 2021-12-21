<?php

namespace App\Http\Controllers\Partners;

use App\CustomerHasUser;
use App\Entities\Partners\Customer;
use App\Entities\Users\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Partners\CustomerCreateRequest;
use App\Http\Requests\Partners\CustomerUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomersController extends Controller
{
    /**
     * Display a listing of the customer.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $customerQuery = Customer::latest()->withCount('projects');
        $customerQuery->where('name', 'like', '%'.request('q').'%');
        $customers = $customerQuery->paginate(25);

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the create customer form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $users = User::join('user_roles', 'users.id', '=', 'user_roles.user_id')->where('role_id', 3)->get();

        return view('customers.create', compact('users'));
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param  \App\Http\Requests\Partners\CustomerCreateRequest  $customerCreateForm
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|max:60',
            'email'   => 'nullable|email|unique:customers,email',
            'phone'   => 'nullable|max:255',
            'pic'     => 'nullable|max:255',
            'address' => 'nullable|max:255',
            'website' => 'nullable|url|max:255',
            'notes'   => 'nullable|max:255',
        ]);

        if( $validator->fails() ) {
            return back()->withErrors($validator)
                        ->withInput();
        }

        $customer = Customer::create([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'pic'   => $request->pic,
            'address'   => $request->address,
            'website'   => $request->website,
            'notes' => $request->notes,
            'is_active' => true,
        ]);

        if( isset($request->customer_has_users) ) {
            foreach( $request->customer_has_users as $user_id ) {
                CustomerHasUser::create([
                    'customer_id'   => $customer->id,
                    'user_id'       => $user_id  
                ]);
            }
        }

        flash(__('customer.created'), 'success');

        return redirect()->route('customers.index');
    }

    /**
     * Show the specified customer.
     *
     * @param  \App\Entities\Partners\Customer  $customer
     * @return \Illuminate\View\View
     */
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the edit customer form.
     *
     * @param  \App\Entities\Partners\Customer  $customer
     * @return \Illuminate\View\View
     */
    public function edit(Customer $customer)
    {
        $users = User::join('user_roles', 'users.id', '=', 'user_roles.user_id')->where('role_id', 3)->get();

        return view('customers.edit', [
            'customer'  => $customer,
            'users'     => $users
        ]);
    }

    /**
     * Update the specified customer in storage.
     *
     * @param  \App\Http\Requests\Partners\CustomerUpdateRequest  $customerUpdateForm
     * @param  \App\Entities\Partners\Customer  $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CustomerUpdateRequest $customerUpdateForm, Customer $customer)
    {
        $customer->update($customerUpdateForm->validated());

        CustomerHasUser::where('customer_id', $customer->id)->delete();
        if( isset($customerUpdateForm->validated()['customer_has_users']) ) {
            foreach( $customerUpdateForm->validated()['customer_has_users'] as $user_id ) {
                CustomerHasUser::create([
                    'customer_id'   => $customer->id,
                    'user_id'       => $user_id  
                ]);
            }
        }

        flash(__('customer.updated'), 'success');

        return redirect()->route('customers.show', $customer->id);
    }

    /**
     * Remove the specified customer from storage.
     *
     * @param  \App\Entities\Partners\Customer  $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Customer $customer)
    {
        // TODO: user cannot delete customer that has been used in other table
        request()->validate(['customer_id' => 'required']);

        if (request('customer_id') == $customer->id && $customer->delete()) {
            flash(__('customer.deleted'), 'warning');

            return redirect()->route('customers.index', request(['page', 'q']));
        }
        flash(__('customer.undeleted'), 'danger');

        return back();
    }
}
