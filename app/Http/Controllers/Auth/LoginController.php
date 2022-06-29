<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => "Hibas adatok",
            ], 500);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token =  $user->createToken('kurutty')->plainTextToken;
            $roles = $user->getRoleNames();
            //dd($roles);
            $iskolas = null;
            if ($roles[0] == "szulo") {
                $iskolas = $user->iskolas->vezetek_nev . " " . $user->iskolas->kereszt_nev;
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Successfull login!',
                //'access_token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name'  =>  $user->name,
                    'name_full' => isset($user->person) ? $user->person->name : 'Szuper Admin',
                    'person_uuid' => isset($user->person) ? $user->person->uuid : null,
                    'email' =>  $user->email,
                    'access_token' => $token,
                    'roles' =>  $user->getRoleNames(),
                    'perms' =>  $user->permissions()->pluck('name')->toArray(),
                ],
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Az email cím vagy jelszó nem talál!',
            ], 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();

        return response()->json(compact('user'));
    }

    public function refresh()
    {
        $token = auth()->refresh();
        return response()->json(compact('token'));
    }

    public function logout(Request $request)
    {
        $user = auth()->user();

        foreach ($user->tokens as $token) {
            $token->delete();
        }
        return response()->json(['message' => 'Successfully logged out']);
    }
}
