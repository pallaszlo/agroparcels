<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class AdminController extends Controller
{

    public function changePassword(Request $request)
    {
        $request->validate([
            //'current_password'  => ['required', new MatchOldPassword],
            'password'          => ['required'],
            'password_confirm'  => ['same:password'],
        ]);

        if (!Hash::check($request['oldpassword'], Auth::user()->password)) {
            return response()->json(['status' => 'error', 'message' => 'A régi jelszó nem talál a megadott jelszóval!'], 500);
        }

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->password)]);
        return response()->json(['status' => 'success', 'message' => 'Sikeres jelszó változtatás!'], 200);
    }

    // Összes felhasználó lekérése
    public function getUsers()
    {
        $users = User::all();
        $data = array();

        foreach ($users as $user) {
            $roles = $user->getRoleNames();
            $role = $roles[0];

            array_push($data, array(
                'id'            => $user->id,
                'nev'           => $user->name,
                'email'         => $user->email,
                'role'          => $role,
            ));
        }
        return response()->json($data);
    }

    // Felhasznalo frissitese
    public function updateUser(Request $request)
    {
        //dd($request);
        try {
            User::where('id', $request->id)->update([
                'name'           => $request->nev,
                'email'          => $request->email,
            ]);
            return response()->json(['status' => 'success', 'message' => 'Felhasználó sikeresen frissítve!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Sikertelen frissítés!'], 500);
        }
    }

    // Felhasznalo letrehozasa
    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => "Az email cím már létezik",
            ], 500);
        }

        DB::beginTransaction();
        $random_password = Str::random(6);
        $password = Hash::make($random_password);
        $name = $request->input('nev');
        $email = $request->input('email');
        try {
            $user = new User([
                'name'          => $name,
                'email'         => $email,
                'password'      => $password,
            ]);
            $user->save();

            $user->assignRole($request->role);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'Sikertelen létrehozás!'], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Sikeres létrehozás!'], 200);
    }

    // Összes felhasználó lekérése
    public function geRoles()
    {
        $roles = Role::all();
        $data = array();
        foreach ($roles as $role) {
            array_push($data, array("text" => $role->name, "value" => $role->id));
        }
        return response()->json($data);
    }

}
