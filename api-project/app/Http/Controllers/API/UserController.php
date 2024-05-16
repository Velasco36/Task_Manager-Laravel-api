<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    //

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password'=>'required|string|min:6|confirmed'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password'=> Hash::make($request->password)
        ]);

        return response()->json([
            'msg'=> 'User Inserted Successfully',
            'user'=>$user
        ]);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=> 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors());
        }
        if (!$token = auth()->guard('api')->attempt($validator->validated())) {
            return response()->json(['success' => false, 'msg' => 'Username & Password is incorrect']);
        }
        return  $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'success'=> true,
            'access_token' => $token,
            'token_type' => 'Bearer',
            // 'expired_in' => config('auth.guards.api.ttl') * 60


        ]);

    }


}
