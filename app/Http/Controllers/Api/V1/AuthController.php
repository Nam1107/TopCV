<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Auth\SessionGuard;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RefreshToken;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Hash;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\UserCollection;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtauth', ['except' => ['login','register','refresh']]);
        $this->middleware('checkpermission:user',['only' => ['me']]);
        
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email'=>'required|string|unique:users',
            'password'=>'required|string|min:8',
            'c_password' => 'required|same:password'
        ]);

        $user = new User([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar'=>'https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.nj.com%2Fentertainment%2F2020%2F05%2Feveryones-posting-their-facebook-avatar-how-to-make-yours-even-if-it-looks-nothing-like-you.html&psig=AOvVaw0ORbck3Xz-oUKq6-Alu4ux&ust=1700070234042000&source=images&cd=vfe&opi=89978449&ved=0CBEQjRxqFwoTCKDIiKmFxIIDFQAAAAAdAAAAABAE',
        ]);

        


        if($user->save()){
            // $tokenResult = $user->createToken('Personal Access Token');
            // $token = $tokenResult->plainTextToken;

            // return response()->json([
            // 'message' => 'Successfully created user!',
            // 'accessToken'=> $token,
            // ],201);

            return response()->json([
                'message' => 'Successfully created user!',
                ],201);
        }
        else{
            return response()->json([
                'error' => 'Provide proper details',
            ], 401);
        }
    }
    

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:8'
        ]
        // ,[
        //     'required'=>':attribute không được bỏ trống',
        //     'email' => ':attribute không hợp lệ',
        //     'min'=> ':attribute mật khẩu quá ngắn'
        // ]
    );


        $credentials = $request->only(['email', 'password']);

        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json([
                'error' => 'Email not required',
            ], 401);
        }
        if (!Hash::check($request->password, $user->password, [])) {
            return response()->json([
                'error' => 'Unauthorized',
            ], 401);
        }

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $refreshToken = [
            'user_id'=>$user->id, 
            'refresh_token'=> $token ,
            'token_type' => 'bearer',
        ];
        RefreshToken::create($refreshToken);
        // $refreshToken->create($refreshToken);

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        

        return new UserResource(auth()->user()->loadMissing('roles'));
    }

    /*
     *
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // auth()->logout();
        $request->validate([
                'refresh_token' => 'required'
            ]);
        $refreshToken['refresh_token'] = $request['refresh_token'];
        RefreshToken::where($refreshToken)->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $token = $request->bearerToken();
        // $token = $request['refresh_token'];
        $id = RefreshToken::where('refresh_token', $token)->firstOrFail();
        $user = User::where('id', $id['user_id'])->firstOrFail();
        $token = auth()->refresh(); // set Black_list is false.
        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 
        ]);
    }
}
