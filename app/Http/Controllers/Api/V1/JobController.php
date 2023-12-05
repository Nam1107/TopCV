<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Company;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\JobResource;
use App\Http\Resources\V1\JobCollection;
use App\Http\Requests\V1\StoreJobRequest;
use App\Http\Requests\V1\UpdateJobRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validation;

class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtauth', ['only' => ['store',
                                                'destroy',
                                                'update',
                                                ]]);
        $this->middleware('checkpermission:manager',['only' => ['store',
                                                                'update',
                                                                'destroy',
                                                                ]]);

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'perPage'=>'numeric|min:5|max:20'
        ]);

        $perPage = $request->query('perPage');
        if(!$perPage){
            $perPage = 15;
        }

        $jobs = Job::where('expire_at','>',now())->with('company');

        return new JobCollection($jobs->paginate($perPage)->appends($request->query()));


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
    public function store(StoreJobRequest $request)
    {
        //
        $user = auth()->user();
        $user_id = $user->id;
        $request['created_by'] = $user_id;
        $company_id = $request->company_id;

        $company = Company::findOrFail($company_id);
        $check = $company->isMember($user_id);
        if(!$check){
            return response()->json([
                'message' => 'You Are Not An Member Of Company',
                'file'  => 'JobController'
            ], 401);
        }

        $job = Job::create($request->all());

        return new JobResource($job);
    }

    /**
     * Display the specified resource.
     */
    public function show(job $job)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(job $job)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, job $job)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(job $job)
    {
        //
    }
    public function listJob(Request $request){
        $perPage = $request->query('perPage');
        $request->validate([
            'perPage'=>'numeric|min:5|max:20'
        ]);
        if(!$perPage){
            $perPage = 15;
        }
        return new JobCollection(Job::paginate($perPage)->appends($request->query()));
    }
}
