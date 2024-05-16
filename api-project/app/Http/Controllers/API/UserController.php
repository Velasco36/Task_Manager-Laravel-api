<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'msg' => 'User Inserted Successfully',
            'user' => $user
        ]);
    }


    public function login(Request $request)
    {
        // Validar las credenciales del usuario
        $credentials = $request->only('email', 'password');

        // Intentar autenticar al usuario
        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        // Generar token JWT
        $token = JWTAuth::attempt($credentials);

        // Devolver el token JWT en la respuesta
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    //profile method
    public function userProfile()
    {
        $user = Auth::user();
        return response()->json(['message' => $user]);
    }
    //logout api method
    public function logout()
    {
        try {
            auth()->logout();

            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e]);
        }
    }

}
