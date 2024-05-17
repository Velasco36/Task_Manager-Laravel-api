<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\PasswordReset;
class UserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forget_Password', 'reset_Passwordload', 'resetPassword']]);
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
        return $this->responWithToken($token);
    }

    private function responWithToken($token){
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

    public function updateProfile(Request $request)
    {
        if (auth()->user()) {

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required|string',
                'email' => 'required|email|string'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
            return response()->json(['success' => true, 'msg' => 'User Date', 'date' => $user]);
        } else {
            return response()->json(['success' => false, 'msg' => 'User is not Authenticated.']);
        }
    }

    public function sedVerifyMail($email)
    {
        if (auth()->user()) {
            $user = User::where('email', $email)->get();
            if (count($user) > 0) {

                $random = Str::random(40);
                $domain = URL::to('/');
                $url = $domain . '/' . $random;

                $data['url'] = $url;
                $data['email'] = $email;
                $data['title'] = "Email Verification";
                $data['body'] = "Pleasee click here to below to verify your mail.";

                Mail::send('verifyMail', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['title']);
                });
                $user = User::find($user[0]['id']);
                $user->remember_token = $random;
                $user->save();

                return response()->json(['success' => true, 'msg' => 'Mail sent Successfully.']);
            } else {
                return response()->json(['success' => false, 'msg' => 'User is not fount.']);
            }
        } else {
            return response()->json(['success' => false, 'msg' => 'User is not Authenticated']);
        }
    }

    public function verificationMail($token)
    {
        $user = User::where('remember_token', $token)->get();
        if (count($user) > 0) {
            $datatime = Carbon::now()->format('Y-m-d :i:s');
            $user = User::find($user[0]['id']);
            $user->remember_token = "";
            $user->email_verify_at = $datatime;
            $user->save();
        } else {
            return view('404');
        }
    }

    public function refreshToken()
    {
        if (auth()->user()) {
            $newToken = $this->responWithToken(auth()->refresh());

            try {
                return response()->json(['error' => $newToken]);

            } catch (JWTException $e) {
                return response()->json(['error' => $e], 500);
            }


        } else {
            return response()->json(['success' => false, 'msg' => 'User is not Authenticated']);
        }
    }

    public function forget_Password(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->get();
            if (count($user) > 0) {
                $token = Str::random(40);
                $domain = URL::to('/');
                $url = $domain . '/reset-Password?token=' . $token;

                $data['url'] = $url;
                $data['email'] = $request->email;
                $data['title'] = "Password Reset";
                $data['body'] = "Please click on below link to reset rour password.";


                Mail::send('forgetPasswordMail', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['title']);
                });


                $datatime = Carbon::now()->format('Y-m-d H:i:s');

                PasswordReset::updateOrCreate(
                    ['email' => $request->email],
                    [
                        'email' => $request->email,
                        'token' => $token,
                        'created_at' => $datatime
                    ]
                );
                return response()->json(['success' => true, 'msg' => 'Please check your mail to reset your Password']);
            } else {
                return response()->json(['success' => false, 'msg' => 'User is not fount.']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function reset_Passwordload(Request $request)
    {
        $resetData = PasswordReset::where('token', $request->token)->first();
        if ($resetData) {
            $user = User::where('email', $resetData->email)->first();
            if ($user) {
                return view('resetPassword', compact('user'));
            }
        }
        return view('resetPassword');
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::find($request->id);
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            return 'password change';
        } else {
            return view('404');
        }

    }
}
