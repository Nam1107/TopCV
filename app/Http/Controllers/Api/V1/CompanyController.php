<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Http\Resources\V1\CompanyResource;
use App\Http\Resources\V1\CompanyCollection;
use App\Http\Requests\V1\StoreCompanyRequest;
use App\Http\Requests\V1\UpdateCompanyRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

use DB;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtauth', ['only' => ['store','destroy','update','addFollow']]);
        $this->middleware('checkpermission:manager',['only' => ['store','update','delete']]);

    }
    public function index(Request $request)
    {

        $id = 2;
        $isFollow = DB::table('company')
        ->select(DB::raw('company.id, company_follow_list.user_id AS isFollowing'))
        ->leftJoin('company_follow_list','company.id','=','company_follow_list.company_id')
        ->where('company_follow_list.user_id','=',$id);

        $comp = Company::select('company.*','is_follow.isFollowing')
        ->leftJoinSub($isFollow , 'is_follow', function ($join) {
            $join->on('company.id', '=', 'is_follow.id');
        });
        return new CompanyCollection($comp->paginate()->appends($request->query()));




        $filter = ['follow_count'=>'6'];
        $owner = $request->query('owner');
        // $company = Company::where($filter)->get();
        // return $company;

        $company = $this->getList();
        
        if($owner){
            $company = $company->with('ownedBy');
        }
        return new CompanyCollection($company );
    }



    public function store(StoreCompanyRequest $request)
    {
        $user = auth()->user();
        $count = Company::where('owner_id',$user['id'])->count();
        if($count >=3){
            return response()->json([
                'error'=>'You have so many companies'
            ],401);

        }

        $request['owner_id'] = $user->id;

        $company = Company::create($request->all());
        return new CompanyResource($company);


    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $user_id = $this->checkUser();
        // $user = User::findOrFail($user_id);
        $test = Company::where('user_id',1)->with('isFollow')->get();
        return $test;
        return new CompanyResource($company->loadMissing('isFollowing',$user));
        if ($company->isFollowing($user)) {
            return 1;
        } else {
            return 0;
        }
        $user_id = $this->checkUser();
        $id = $company->id;

        $check = Company::whereHas('isFollow', function($q) use ($user_id)
        {
            $q->where('user_id','=', $user_id);
        })->find($id);

        if($check){
            $company['is_follow'] = 1;
        }

        $includeInvoices = request()->query('owner');
        if($includeInvoices){
                return new CompanyResource($company->loadMissing('ownedBy'));
        }
        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {

        if($user['id'] !== $company['owner_id']){
            return response()->json([
                'error' => 'You Do Not Have Permission To Access',
            ], 401);
        }
        $company->update($request->all());
        return new CompanyResource($company->loadMissing('ownedBy'));
    }
    public function destroy(Company $company)
    {
        //
        $user = auth()->user();
        if($user['id'] !== $company['owner_id']){
            return response()->json([
                'error' => 'You Do Not Have Permission To Access',
            ], 401);
        }
        $company->delete();
        return response()->json([
            'message' => 'Deleted successfully',
        ], 200);
    }
    public function addFollow($id){
        $company = Company::findOrFail($id);
        $user_id = auth()->user()->id;

        $table = \DB::select("SELECT company.*, company_follow_list.user_id
        FROM company
        Left JOIN company_follow_list
        ON company.id = company_follow_list.company_id
        WHERE company_follow_list.user_id = ?
        AND company.id = ?
        ",[$user_id,$id]);

        if($table){
            return new CompanyResource($company);
        }

        \DB::table('Company_follow_list')->create([
            'user_id'=>$user_id,
            'company_id'=>$id
        ]);
        
        $company->increment('follow_count',1,[]);
        return new CompanyResource($company);
    }
    public function checkUser(){
        $user = auth()->user();
        if(!$user){
            return 0;
        }
         return $user->id;

    }
    public function getList($user = null){
        $company = DB::select('SELECT company.*,A.isFollowing
        FROM company
        Left JOIN (SELECT company.*, company_follow_list.user_id AS isFollowing
        FROM company
        Left JOIN company_follow_list
        ON company.id = company_follow_list.company_id
        WHERE company_follow_list.user_id = ?) AS A
        ON company.id = A.id
        ',[$user]);
        return $company;
    }
}
