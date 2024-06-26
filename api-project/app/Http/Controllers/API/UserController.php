<?php

namespace App\Http\Controllers\API;

use App\Models\Note;
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

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'logout', 'register', 'forget_Password', 'reset_Passwordload', 'resetPassword', 'detailNote']]);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Registrar un nuevo usuario
     * @OA\Post (
     *     path="/api/register",
     *     tags={"Usuario"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del usuario a registrar",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", example="miguel"),
     *                 @OA\Property(property="last_name", type="string", example="velasco"),
     *                 @OA\Property(property="username", type="string", example="miguel"),
     *                 @OA\Property(property="email", type="string", format="email", example="admin3@admin.com"),
     *                 @OA\Property(property="password", type="string", example="123456"),
     *                 @OA\Property(property="password_confirmation", type="string", example="123456")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="User Inserted Successfully"),
     *              @OA\Property(property="user", type="object",
     *                  @OA\Property(property="name", type="string", example="miguel"),
     *                  @OA\Property(property="last_name", type="string", example="velasco"),
     *                  @OA\Property(property="username", type="string", example="miguel"),
     *                  @OA\Property(property="email", type="string", format="email", example="admin3@admin.com"),
     *                  @OA\Property(property="updated_at", type="string", example="2024-05-21T22:21:05.000000Z"),
     *                  @OA\Property(property="created_at", type="string", example="2024-05-21T22:21:05.000000Z"),
     *                  @OA\Property(property="id", type="number", example=5)
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:100',
            'last_name' => 'required|string|min:2|max:100',
            'username' => 'required|string|min:2|max:100||unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'msg' => 'User Inserted Successfully',
            'user' => $user
        ]);
    }


    /**
     * Iniciar sesión de usuario
     * @OA\Post (
     *     path="/api/login",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Credenciales de inicio de sesión",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string", format="email", example="admin3@admin.com"),
     *                 @OA\Property(property="password", type="string", example="123456")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Login successful"),
     *              @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */

    public function login(Request $request)
    {
        // Validar las credenciales del usuario
        $credentials = $request->only('email', 'password');

        // Buscar al usuario por email
        $user = User::where('email', $credentials['email'])->first();

        // Si el usuario no existe, retornar un error
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }


        // Intentar autenticar al usuario
        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = JWTAuth::attempt($credentials);
            return response()->json([
                'msg' => 'Login successful',
                'token' => $token
            ]);
        }

        $token = JWTAuth::attempt($credentials);

        // Devolver el token JWT en la respuesta
        return $this->respondWithToken($token);
    }

    private function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Obtener perfil de usuario
     * @OA\Get (
     *     path="/api/userProfile",
     *     tags={"Usuario"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="object",
     *                  @OA\Property(property="id", type="number", example=5),
     *                  @OA\Property(property="name", type="string", example="miguel"),
     *                  @OA\Property(property="last_name", type="string", example="velasco"),
     *                  @OA\Property(property="username", type="string", example="miguel"),
     *                  @OA\Property(property="email", type="string", format="email", example="admin3@admin.com"),
     *                  @OA\Property(property="email_verified_at", type="string", format="date-time", example=null),
     *                  @OA\Property(property="is_verify", type="number", example=0),
     *                  @OA\Property(property="created_at", type="string", example="2024-05-21T22:21:05.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", example="2024-05-21T22:21:05.000000Z")
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */

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

    /**
     * Actualizar perfil de usuario
     * @OA\Post (
     *     path="/api/profile-update",
     *     tags={"Usuario"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos actualizados del perfil de usuario",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(property="id", type="string", example="5"),
     *                 @OA\Property(property="name", type="string", example="miguel"),
     *                 @OA\Property(property="last_name", type="string", example="velasco"),
     *                 @OA\Property(property="username", type="string", example="miguel"),
     *                 @OA\Property(property="email", type="string", format="email", example="admin4@admin.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="msg", type="string", example="User Date"),
     *              @OA\Property(property="date", type="object",
     *                  @OA\Property(property="id", type="number", example=5),
     *                  @OA\Property(property="name", type="string", example="miguel"),
     *                  @OA\Property(property="last_name", type="string", example="velasco"),
     *                  @OA\Property(property="username", type="string", example="miguel"),
     *                  @OA\Property(property="email", type="string", format="email", example="admin4@admin.com"),
     *                  @OA\Property(property="email_verified_at", type="string", format="date-time", example=null),
     *                  @OA\Property(property="is_verify", type="number", example=0),
     *                  @OA\Property(property="created_at", type="string", example="2024-05-21T22:21:05.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", example="2024-05-21T22:53:10.000000Z")
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */

    public function updateProfile(Request $request)
    {
        if (auth()->user()) {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required|string',
                'last_name' => 'required|string',
                'username' => 'required|string',
                'email' => 'required|email|string'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            $user = User::find($request->id);
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->save();
            return response()->json(['success' => true, 'msg' => 'User Date', 'date' => $user]);
        } else {
            return response()->json(['success' => false, 'msg' => 'User is not Authenticated.']);
        }
    }

    public function sendVerifyMail($email)
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

    /**
 * Solicitar restablecimiento de contraseña
 * @OA\Post (
 *     path="/api/forget-password",
 *     tags={"Autenticación"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Correo electrónico del usuario para restablecer la contraseña",
 *         @OA\MediaType(
 *             mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(
 *                 @OA\Property(property="email", type="string", format="email", example="admin4@admin.com")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *              @OA\Property(property="success", type="boolean", example=true),
 *              @OA\Property(property="msg", type="string", example="Please check your mail to reset your Password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     ),
 *     security={}
 * )
 */

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
