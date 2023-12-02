<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Member;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Http\Resources\V1\CompanyResource;
use App\Http\Resources\V1\CompanyCollection;
use App\Http\Resources\V1\UserCollection;
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
        $manager_id = $company->owner_id;
        if($manager_id != $user->id){
            return response()->json([
                'message' => 'You Are Not The Manager Of Company',
                'file'  => 'MemberController'
            ], 401);
        }
        $role = $member->role();
        $check = $company->isMember($member_id);

        if($check){
            return response()->json([
                'message' => "User already is member",
                'file'  => 'MemberController'
            ], 401);
        }
        if($role->id >2){
            return response()->json([
                'message' => "You can't employ this user",
                'file'  => 'MemberController'
            ], 401);
        }
        
        if($role->id == 1){
            \App\Models\Role_user::firstOrCreate([
                'role_id' => 2,
                'user_id' => $member_id,
            ]);
        }
        \App\Models\Member::firstOrCreate([
            'member_id' => $member_id,
            'company_id'=>$company_id,
        ]);
        return new UserResource($member->refresh()->loadMissing('roles'));
        
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

            if($manager_id != $company->owner_id){
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
            // $owner_id = $company->owner_id;
            // if($owner_id != $user->id){
            //     return response()->json([
            //         'message' => 'You Do Not Have Permission To Access',
            //     ], 401);
            // }

            // return Arr::except($arr,['customerId','billedDate','paidDate']);
        }
        return 1;
        

        \App\Models\Role_user::where([
            'role_id' => 2,
            'user_id' => $user_id,
        ])->delete();

        return new UserResource($user->refresh()->loadMissing('roles'));
        
    }
}
