<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\UserCollection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('jwtauth', ['only' => ['']]);
        $this->middleware('checkpermission:admin',['only' => ['store','update','delete']]);

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
        return new UserCollection(User::paginate(5));
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
    public function update(Request $request, string $id)
    {
        //
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
