<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Customer;


use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CustomerResource;
use App\Http\Resources\V1\CustomerCollection;
use App\Filters\V1\CustomerFilter;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter =  new CustomerFilter();
        
        $filterItems = $filter->transform($request);
        
        //
        // print_r($queryItems);
        // die;
        $includeInvoices = $request->query('includeInvoices');
        $customers = Customer::where($filterItems);
        if($includeInvoices){
            $customers = $customers->with('invoices');
        }
        return new CustomerCollection($customers->paginate()->appends($request->query()));
        // $queryItems = $filter->transform($request);
        // if(count($queryItems)==0){
        //     return new CustomerCollection(Customer::paginate());
        // }else{
        //     $customer = Customer::where($queryItems)->paginate();
        //     return new CustomerCollection($customer->appends($request->query()));
        // }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $newRequest = Request::capture();
        $newRequest->replace($request->except(['postalCode']));
        // return $newRequest->all();
        return new CustomerResource(Customer::create($newRequest->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
        $includeInvoices = request()->query('includeInvoices');
        if($includeInvoices){
                return new CustomerResource($customer->loadMissing('invoices'));
        }
        return new CustomerResource($customer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
        $customer->update($request->all());
        return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}