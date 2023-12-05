<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Member;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Http\Resources\V1\CompanyResource;
use App\Http\Resources\V1\CompanyCollection;
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Http\Requests\V1\StoreCompanyRequest;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtauth', ['only' => [
                                                'listMember',
                                                'addMember',
                                                'removeMember',
                                                ]]);
        $this->middleware('checkpermission:manager',['only' => [
                                                                'listMember',
                                                                'addMember',
                                                                'removeMember',
                                                                ]]);

    }

    public function addMember(string $company_id,string $member_id){
        
        $member = User::findOrFail($member_id);
        $company = Company::findOrFail($company_id);
        $user = auth()->user();
        $manager_id = $company->manager_id;
        if($manager_id != $user->id){
            return response()->json([
                'message' => 'You Are Not The Manager Of Company',
                'file'  => 'MemberController'
            ], 401);
        }
        $check = $company->isMember($member_id);
        if($check){
            return response()->json([
                'message' => "User already is member",
                'file'  => 'MemberController'
            ], 401);
        }
        
        \App\Models\Member::create([
            'member_id' => $member_id,
            'company_id'=> $company_id,
        ]);
        return new UserResource($member->refresh()->loadMissing('roles'));
        
    }

    public function isMember(string $company_id,string $member_id){
        $member = User::findOrFail($member_id);
        $company = Company::findOrFail($company_id);
        $check = $company->isMember($member_id);
        return $check;

    }

    public function removeMember(Request $request,string $member_id){
        $member = User::findOrFail($member_id);
        $request->validate(
            [
                '*.company_id'=>['required','numeric'],
            ]
        );
        $manager = auth()->user();
        $manager_id = $manager->id;
        // $studentIds = collect($request->all())->pluck('company_id');
        // return $studentIds;
        $array_req = $request->all();

        foreach ($array_req as $value){
            $company_id = $value['company_id'];
            $company = Company::findOrFail($company_id);

            if($manager_id != $company->manager_id){
                return response()->json([
                    'message' => 'You Are Not The Manager Of Company',
                    'file'  => 'MemberController'
                ], 401);
            }

            $check = $company->isMember($member_id);
            if(!$check){
                return response()->json([
                    'message' => 'UserID '.$member_id .' Is Not An Member Of Company',
                    'file'  => 'MemberController'
                ], 401);
            }
        };

        $array_req = $array_req->map(function($item) {
            return array_merge($item, [
                'user_id' => $member_id
            ]);
        });

        return $array_req;

        \App\Models\Member::where([
            'role_id' => 2,
            'user_id' => $user_id,
        ])->delete();

        return new UserResource($user->refresh()->loadMissing('roles'));
        
    }

    public function listMember(Request $request,string $company_id){
        $company = Company::findOrFail($company_id);
        $manager_id = $company->manager_id;
        // $manager = $company->managedBy();
        
        $members = User::select('users.*')
                    ->join('members','users.id','=','members.member_id')
                    ->where('members.company_id','=',$company_id)
                    ->where('members.member_id','!=',$manager_id);
        // $mergeTbl = $manager->unionAll($members);
        // return new UserCollection($mergeTbl->paginate());
        $perPage = $request->query('perPage');
        $request->validate([
            'perPage'=>'numeric|min:5|max:20'
        ]);
        if(!$perPage){
            $perPage = 15;
        }
        return new UserCollection($members->paginate($perPage));

    }
}
