<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\UserCollection;
use App\Http\Requests\V1\UpdateUserRequest;
use App\Http\Requests\V1\StoreUserRequest;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('jwtauth', ['only' => ['store','update','delete','updateRole']]);
        $this->middleware('checkpermission:admin',['only' => ['store','update','delete','updateRole']]);
        $this->middleware('checkpermission:manager',['only' => []]);

    }
    public function checkPermission(User $user,$role){
        
        $roles =  $user->roles ;
        $permission = $roles->filter(function($item) use($role){
           return $item->role_name == $role;
        })->first();
        if($permission){
            abort( response()->json([
                "message"=>'You Do Not Have Permission To Access'
            ], 401) );
        }
    }
    public function index(Request $request)
    {
        $perPage = $request->query('perPage');
        $request->validate([
            'perPage'=>'numeric|min:5|max:20'
        ]);
        if(!$perPage){
            $perPage = 15;
        }
        return new UserCollection(User::paginate($perPage)->appends($request->query()));
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user_role = request()->query('roles');
        if($user_role){
            return new UserResource($user->loadMissing('roles'));
        }
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
        $user->update($request->all());
        return new UserResource($user->loadMissing('roles'));
        
    }



    public function updateRole(Request $request,string $user_id){
        $role_arr = ['user','manager','admin'];
        $user = User::findOrFail($user_id);
        $request->validate([
            'role'=>['required',Rule::in($role_arr)]
        ]);
        $role = $request['role'];

        $roles =  $user->roles ;
        $checkRole = $roles->last()->role_name;

        if($checkRole==$role) {
            return new UserResource($user->loadMissing('roles'));
        }

        if($role == 'admin'){
            return response()->json([
                'message' => 'You Do Not Have Permission To Access',
                'file'  => 'UserController:updateRole'
            ], 401);
        }
        
        
        $i=-1;
        $user_arr = array();
        do{
            $i++;
            array_push($user_arr,[
                'role_id' => 1+$i,
                'user_id' => $user_id
            ]);
            
        }while($role_arr[$i] != $role);

        \App\Models\Role_user::where('user_id',$user_id)->delete();
        \App\Models\Role_user::insert($user_arr);


        return new UserResource($user->refresh()->loadMissing('roles'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
