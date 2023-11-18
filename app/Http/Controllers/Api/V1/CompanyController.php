<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Http\Resources\V1\CompanyResource;
use App\Http\Resources\V1\CompanyCollection;
use App\Http\Requests\V1\StoreCompanyRequest;
use App\Http\Requests\V1\UpdateCompanyRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtauth', ['only' => ['store','destroy']]);
    }
    public function index(Request $request)
    {
        //
        // return new CompanyCollection(Company::paginate(5));
        $filter = ['follow_count'=>'0'];
        $owner = $request->query('owner');
        $company = Company::where($filter);
        
        if($owner){
            $company = $company->with('ownedBy');
        }
        return new CompanyCollection($company->paginate()->appends($request->query()));
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
    public function store(StoreCompanyRequest $request)
    {
        $user = auth()->user();

        $request['owner_id'] = $user->id;

        $company = Company::create($request->all());
        return new CompanyResource($company);


    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
        $includeInvoices = request()->query('owner');
        if($includeInvoices){
                return new CompanyResource($company->loadMissing('ownedBy'));
        }
        return new CompanyResource($company);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}
