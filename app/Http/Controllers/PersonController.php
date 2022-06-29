<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Operation;
use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use App\Models\Work;
use Exception;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PDO;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all Persons with uuid and name and return them as JSON
        $persons = Person::all();
        $data = [];
        foreach($persons as $person){
            array_push($data, [
                'uuid' => $person->uuid,
                'name' => $person->name,
                'group_uuid' => $person->group_uuid,
                'group_name' => $person->group->name,
                'email'  => $person->user->email ?? "",
            ]);
        }
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:55',
            'group_uuid' => 'required|string|max:36',
            'username' => 'string|max:55',
            'user_id' => 'integer',
            'email' => 'email',
            'password' => 'string|min:6|max:255',
            'roll' => 'string|max:55',
            'works' => 'array',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen létrehozás",
                'errors' => $validator->errors()
            ], 500);
        }
        try{
            $person = '';
            if($request->user_id != null){
                $user = User::find($request->user_id)->first();
                if($user == null){
                    throw new Exception("Nem létező felhasználó");
                }
                $person = Person::create([
                    'name' => $request->name,
                    'group_uuid' => $request->group_uuid,
                    'user_id' => $request->user_id,
                ]);
            }
            else{
                if($request->email == null || $request->password == null || $request->roll == null){
                    throw new Exception("Email és jelszó megadása kötelező");
                }
                $user = User::create([
                    'name' => $request->username,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                ]);
                $user->assignRole($request->roll);

                $person = Person::create([
                    'name' => $request->name,
                    'group_uuid' => $request->group_uuid,
                    'user_id' => $user->id,
                ]);
            }
            if($request->works != null){
                foreach($request->works as $work){
                    $person->works()->attach($work);
                }
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen létrehozás",
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $person = Person::with(['operations', 'work_machines', 'works'])->where('uuid', $id)->first();

        $data = [
            'uuid' => $person->uuid,
            'name' => $person->name,
            'group_uuid' => $person->group_uuid,
            'group_name' => $person->group->name,
            'email'  => $person->user->email ?? "",
            'roll' => $person->user->getRoleNames()[0] ?? "",
            'username' => $person->user->name ?? "",
            'operations' => [],
            'work_machines' => [],
            'works' => [],
        ];

        foreach($person->operations as $operation){
            array_push($data['operations'], [
                'uuid' => $operation->uuid,
                'operation_name' => $operation->name,
                'work_uuid' => $operation->work_uuid,
                'work_name' => $operation->work->name,
                'layer_name' => $operation->layer->name,
                'layer_uuid' => $operation->layer_uuid,
                'season_name' => $operation->season->name,
                'hours_worked' => $operation->pivot->hours_worked,
            ]);
        }

        foreach($person->work_machines as $work_machine){
            array_push($data['work_machines'], [
                'uuid' => $work_machine->uuid,
                'work_machine_name' => $work_machine->name,
                'group_uuid' => $work_machine->group_uuid,
            ]);
        }

        $works = Work::all();

        foreach($works as $work){
            array_push($data['works'], [
                'uuid' => $work->uuid,
                'work_name' => $work->name,
            ]);
        }

        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $person = Person::with(['operations', 'work_machines', 'works'])->where('uuid', $id)->first();

        $data = [
            'name'  => $person->name,
            'username' => $person->user->name ?? "",
            'email' => $person->user->email ?? "",
            'role' => $person->user->getRoleNames()[0] ?? "",
            'group_uuid' => $person->group_uuid,
            'works' => Work::all(),
            'attached_works' => $person->works,
            'groups' => $this->getPossibleGroups($request),
            'roles' => $this->getPossibleRoles($request)
        ];

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $person = Person::where('uuid', $id)->first();
        //validate request and update person
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:55',
            'group_uuid' => 'string|max:36',
            'username' => 'string|max:55',
            'email' => 'email',
            'roll' => 'string|max:55',
            'works' => 'array',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen létrehozás",
                'errors' => $validator->errors()
            ], 500);
        }
        try{
           $user = User::find($person->user_id);
           $user->update([
               'name' => $request->username,
               'email' => $request->email,
           ]);

            //$user->assignRole($request->roll);

            $person->update([
                'name' => $request->name,
                'group_uuid' => $request->group_uuid,
            ]);

            if($request->works != null){
                $person->works()->sync($request->works);
            }
            else{
                $person->works()->detach();
            }

        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen létrehozás",
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        //delete parcel with uuid
        try{
            Person::where('uuid', $id)->delete();
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen törlés",
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function getPossibleGroups(Request $request){
        $user = $request->user();
        $data = [];

        if($user->hasRole('admin')){
            $person = Person::where('user_id', $user->id)->first();

            if($person->group->is_company_leader == true ){
                $data = Group::where('company_uuid', $person->group->company_uuid)->get(['uuid', 'name']);
            }
            else{
                $data[] = Group::where('uuid', $person->group_uuid)->first(['uuid', 'name']);
            }
        }
        else if($user->hasRole('super-admin')){
            $data = Group::all(['uuid', 'name']);
        }
        else{
            return response()->json([
                'status' => 'error',
                'message' => "Nincs jogosultságod",
            ], 500);
        }

        return $data;
    }

    public function getPossibleRoles(Request $request){
        $data = [];
        $user = $request->user();

        if($user->hasRole('super-admin')){
            $roles = Role::where('name', '!=', 'super-admin')->get();

            foreach($roles as $role){
                array_push($data, [
                    'uuid' => $role->uuid,
                    'name' => $role->name,
                ]);
            }
        }
        else if($user->hasRole('admin')){
            $person = Person::where('user_id', $user->id)->first();

            if($person->group->is_company_leader == true ){
               $roles = Role::where('name', '!=', 'super-admin')->get();

                foreach($roles as $role){
                    array_push($data, [
                        'uuid' => $role->uuid,
                        'name' => $role->name,
                    ]);
                }
            }
            else{
               $role = Role::where('name', 'user')->first();

                array_push($data, [
                    'uuid' => $role->uuid,
                    'name' => $role->name,
                ]);
            }
        }
        else{
            return response()->json([
                'status' => 'error',
                'message' => "Nincs jogosultságod",
            ], 500);
        }

        return $data;
    }

    public function getAttachableWorks($personUuid){
        //find works that are not attached to the person
        $person = Person::where('uuid', $personUuid)->first();
        $works = Work::whereNotIn('uuid', $person->works->pluck('uuid'))->get();
        return response()->json($works);
    }

    public function removeWork($personUiid, $workUuid){
        try{
            $person = Person::where('uuid', $personUiid)->first();
            $work = Work::where('uuid', $workUuid)->first();
            $person->works()->detach($work);

            return response()->json([
                'status' => 'success',
                'message' => "Sikeres törlés",
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen törlés",
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function removeOperation($personUiid, $operationUuid){
        try{
            $person = Person::where('uuid', $personUiid)->first();
            $operation = Operation::where('uuid', $operationUuid)->first();
            $person->operations()->detach($operation);

            return response()->json([
                'status' => 'success',
                'message' => "Sikeres törlés",
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen törlés",
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function addWork($personUiid, $workUuid){
        try{
            $person = Person::where('uuid', $personUiid)->first();
            $work = Work::where('uuid', $workUuid)->first();
            $person->works()->attach($work);

            return response()->json([
                'status' => 'success',
                'message' => "Sikeres hozzáadás",
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen törlés",
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function addOperation($personUiid, $operationUuid, Request $request){
        $validator = Validator::make($request->all(), [
            'hours_worked' => 'numeric|gt:0',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen létrehozás",
                'errors' => $validator->errors()
            ], 500);
        }
        if($request->hours_worked == null){
            $request->hours_worked = 0;
        }
        try{
            $person = Person::where('uuid', $personUiid)->first();
            $operation = Operation::where('uuid', $operationUuid)->first();
            $person->operations()->attach($operation, ['hours_worked' => $request->hours_worked]);

            return response()->json([
                'status' => 'success',
                'message' => "Sikeres hozzáadás",
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen törlés",
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function updateOperation($personUiid, $operationUuid, Request $request){
        $validator = Validator::make($request->all(), [
            'hours_worked' => 'numeric|gt:0',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen létrehozás",
                'errors' => $validator->errors()
            ], 500);
        }
        if($request->hours_worked == null){
            $request->hours_worked = 0;
        }
        try{
            $person = Person::where('uuid', $personUiid)->first();
            $operation = Operation::where('uuid', $operationUuid)->first();
            $person->operations()->updateExistingPivot($operation, ['hours_worked' => $request->hours_worked]);

            return response()->json([
                'status' => 'success',
                'message' => "Sikeres hozzáadás",
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen törlés",
                'errors' => $e->getMessage()
            ], 500);
        }
    }
    public function create(Request $request)
    {
        $data = [
            'roles' => $this->getPossibleRoles($request),
            'groups' => $this->getPossibleGroups($request),
            'works' => Work::all(),
        ];
        return response()->json($data);
    }
}
