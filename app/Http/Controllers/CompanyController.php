<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyCollection;
use Error;
use Exception;

use function Laravel\Prompts\error;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return new CompanyCollection(Company::paginate(5));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name_company' => 'required',
            'phone_company' => 'required',
            'address_company' => 'required',
            'email_company' => 'required',
            'city_company' => 'required'
        ]);
        $company = Company::create($request->all());
        return new CompanyResource($company);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        //
        // $request->validate([
        //     'name_company' => 'required',
        //     'phone_company' => 'required',
        //     'address_company' => 'required',
        //     'email_company' => 'required',
        //     'city_company' => 'required'
        // ]);
        $company->update($request->all());
        return new CompanyResource($company);
        // $data = $request->all();
        // $company = Company::find();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
        try {
            $company->delete();
        } catch (Error $e) {
            echo $e;
        }
        echo 'Complete';
    }
}