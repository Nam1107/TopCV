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
                                                'listEmployer',
                                                // 'upToEmployer',
                                                ]]);
        $this->middleware('checkpermission:manager',['only' => ['store',
                                                                'update',
                                                                'destroy',
                                                                // 'upToEmployer',
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
        // $owner = $request->query('owner');
        // $company = Company::where($filter)->get();
        // return $company;

        $owner = $request->query('owner');
        
        $request->validate([
            'perPage'=>'numeric|min:5|max:20'
        ]);

        $perPage = $request->query('perPage');
        if(!$perPage){
            $perPage = 15;
        }
        if($owner){
            $company = $company->with('ownedBy');
        }

        return new CompanyCollection($company->paginate($perPage)->appends($request->query()));

    }



    public function store(StoreCompanyRequest $request)
    {
        $user_id = $this->checkUser();
        $count = Company::where('owner_id',$user_id)->count();
        if($count >=3){
            return response()->json([
                'error'=>'You have so many companies'
            ],401);

        }
        $request['owner_id'] = $user_id;

        $company = Company::create($request->all());
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

        $includeInvoices = request()->query('owner');
        if($includeInvoices){
                return new CompanyResource($cop->loadMissing('ownedBy'));
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
        $owner_id = $company->owner_id;

        if($user_id !== $owner_id){
            return response()->json([
                'error' => 'You Do Not Have Permission To Access',
            ], 401);
        }
        $company->update($request->all());
        
        $company = Company::select('company.*','user_id as isFollowing')
                            ->leftJoin('company_follow_list',function ($join) use($user_id){
                                $join->on('company_id','=','company.id');
                                $join->where('user_id','=',$user_id);
                            })
                            ->where('company.id',$company_id)->first();
        return new CompanyResource($company->loadMissing('ownedBy'));
    }
    public function destroy(Company $company)
    {
        //
        $user_id = $this->checkUser();
        $company_id = $company->id;
        $owner_id = $company->owner_id;
 
        if($user_id !== $owner_id){
            return response()->json([
                'error' => 'You Do Not Have Permission To Access',
            ], 401);
        }
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

        $company = Company::select('company.*','company_follow_list.user_id as isFollowing')
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

    public function upToEmployer(Request $request,string $company_id,string $user_id){
        $user = User::findOrFail($user_id);
        return $user->role();
        $this->checkPermission($user,'employer');

        \App\Models\Role_user::insert([
            'role_id' => 2,
            'user_id' => $user_id,
        ]);

        return new UserResource($user->refresh()->loadMissing('roles'));
        
    }

    public function fireEmployer(Request $request,string $user_id){
        $user = User::findOrFail($user_id);
        
        $this->checkPermission($user,'manager');
        $roles =  $user->roles ;
        $checkRole = $roles->last()->role_name;

        if($checkRole=='user') {
            return new UserResource($user->loadMissing('roles'));
        }

        \App\Models\Role_user::where([
            'role_id' => 2,
            'user_id' => $user_id,
        ])->delete();

        return new UserResource($user->refresh()->loadMissing('roles'));
        
    }

}
