<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Http\Resources\V1\CompanyResource;
use App\Http\Resources\V1\CompanyCollection;
use App\Http\Resources\V1\UserCollection;
use App\Http\Requests\V1\StoreCompanyRequest;
use App\Http\Requests\V1\UpdateCompanyRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validation;

use DB;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtauth', ['only' => ['store',
                                                'destroy',
                                                'update',
                                                'addFollow',
                                                'unFollow',
                                                'listFollowing',
                                                'listFollowers',
                                                'listMember',
                                                'upToMember',
                                                'myCompany'
                                                ]]);
        $this->middleware('checkpermission:manager',['only' => ['store',
                                                                'update',
                                                                'destroy',
                                                                'listFollowers',
                                                                // 'listMember',
                                                                'upToMember',
                                                                'myCompany',
                                                                ]]);

    }

    public function checkUser(){
        $user = auth()->user();
        if(!$user){
            return 0;
        }
         return $user->id;

    }

    public function index(Request $request)
    {

        $id = $this->checkUser();
        $isFollow = DB::table('company')
        ->select(DB::raw('company.id, company_follow_list.user_id AS isFollowing'))
        ->leftJoin('company_follow_list','company.id','=','company_follow_list.company_id')
        ->where('company_follow_list.user_id','=',$id);

        $company = Company::select('company.*','is_follow.isFollowing')
        ->leftJoinSub($isFollow , 'is_follow', function ($join) {
            $join->on('company.id', '=', 'is_follow.id');
        });

        // $filter = ['follow_count'=>'6'];
        // $manager = $request->query('manager');
        // $company = Company::where($filter)->get();
        // return $company;

        $manager = $request->query('manager');
        
        $request->validate([
            'perPage'=>'numeric|min:5|max:20'
        ]);

        $perPage = $request->query('perPage');
        if(!$perPage){
            $perPage = 15;
        }
        if($manager){
            $company = $company->with('managedBy');
        }

        return new CompanyCollection($company->paginate($perPage)->appends($request->query()));

    }



    public function store(StoreCompanyRequest $request)
    {
        $user_id = $this->checkUser();
        $count = Company::where('manager_id',$user_id)->count();
        if($count >=3){
            return response()->json([
                'message'=>'You have so many companies'
            ],401);

        }
        $request['manager_id'] = $user_id;
        $request['follow_count'] = 1;

        $company = Company::create($request->all());
        $company_id = $company->id;
        \App\Models\Member::create([
            'member_id' => $user_id ,
            'company_id'=>$company_id,
        ]);
        return new CompanyResource($company);


    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $user_id = $this->checkUser();
        $company_id = $company->id;
        $cop = Company::select('company.*','user_id as isFollowing')
                            ->leftJoin('company_follow_list',function ($join) use($user_id){
                                $join->on('company_id','=','company.id');
                                $join->where('user_id','=',$user_id);
                            })
                            ->where('company.id',$company_id)->first();

        $includeInvoices = request()->query('manager');
        if($includeInvoices){
                return new CompanyResource($cop->loadMissing('managedBy'));
        }
        return new CompanyResource($cop);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $user_id = $this->checkUser();
        $company_id = $company->id;
        $manager_id = $company->manager_id;

        if($user_id !== $manager_id){
            return response()->json([
                'message' => 'You Do Not Have Permission To Access',
            ], 401);
        }
        $company->update($request->all());
        
        $company = Company::select('company.*','user_id as isFollowing')
                            ->leftJoin('company_follow_list',function ($join) use($user_id){
                                $join->on('company_id','=','company.id');
                                $join->where('user_id','=',$user_id);
                            })
                            ->where('company.id',$company_id)->first();
        return new CompanyResource($company->loadMissing('managedBy'));
    }
    public function destroy(Company $company)
    {
        $company_id = $company->id;
        $members = \App\Models\Member::where(
            ['company_id'=>$company_id,
            'end_date'=>null
        ])->update([
            'end_date'=>now()
        ]);

        $user_id = $this->checkUser();
        $company_id = $company->id;
        $manager_id = $company->manager_id;
 
        if($user_id !== $manager_id){
            return response()->json([
                'message' => 'You Do Not Have Permission To Access',
            ], 401);
        }
        $member = \App\Models\Member::where(['company_id'=>$company_id])->delete();
        $company->delete();
        return response()->json([
            'message' => 'Deleted successfully',
        ], 200);
    }
    public function addFollow(string $company_id){
        $company = Company::findOrFail($company_id);
        $user_id = $this->checkUser();

        $table = \App\Models\Company_follow_list::where('user_id',$user_id)->where('company_id',$company_id)->first();
        if($table){
            return $this->show($company);
        }

        \App\Models\Company_follow_list::create([
            'user_id'=>$user_id,
            'company_id'=>$company_id
        ]);
        
        $company->increment('follow_count',1,[]);
        return $this->show($company);
    }
    public function unFollow(string $company_id){
        $company = Company::findOrFail($company_id);
        $user_id = $this->checkUser();

        \App\Models\Company_follow_list::where([
            'user_id'=>$user_id,
            'company_id'=>$company_id
        ])->delete();
        
        $company->decrement('follow_count',1,[]);
        return $this->show($company);
    }


    public function listFollowers(Request $request,string $company_id){
        $company = Company::findOrFail($company_id);
        $user = \App\Models\User::join('company_follow_list','users.id','=','company_follow_list.user_id')
                            ->where('company_follow_list.company_id',$company_id);
        $perPage = $request->query('perPage');
        $request->validate([
            'perPage'=>'numeric|min:5|max:20'
        ]);
        if(!$perPage){
            $perPage = 15;
        }

        return new UserCollection($user->paginate($perPage)->appends($request->query()));

    }

    public function listFollowing(Request $request){
        $user_id = $this->checkUser();

        // $company = Company::select('company.*','company_follow_list.user_id as isFollowing')
        //                     ->join('company_follow_list','company.id','=','company_follow_list.company_id')
        //                     ->where('company_follow_list.user_id',$user_id);
        $company = Company::select('company.*')
                            ->join('company_follow_list','company.id','=','company_follow_list.company_id')
                            ->where('company_follow_list.user_id',$user_id);
        $perPage = $request->query('perPage');
        $request->validate([
            'perPage'=>'numeric|min:5|max:20'
        ]);
        if(!$perPage){
            $perPage = 15;
        }

        return new CompanyCollection($company->paginate($perPage)->appends($request->query()));

    }

    public function myCompany(Request $request){
        $user_id = $this->checkUser();
        $company = Company::where(['manager_id' =>$user_id]);
        return new CompanyCollection($company->paginate()->appends($request->query()));

    }

    public function listJob(string $company_id){
        $company = Company::findOrFail($company_id);
        $jobs = $company->listJob();
        return $jobs;
    }



    



}
