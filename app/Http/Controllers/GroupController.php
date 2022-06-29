<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $groupsUuids = $user->getPossibleGroups();

        $groups = Group::with('company')->whereIn('uuid', $groupsUuids)->get(['uuid', 'name', 'active', 'company_uuid']);

        return response()->json($groups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        $data = [];

        foreach($companies as $company){
            array_push($data, [
                'uuid' => $company->uuid,
                'name' => $company->name
            ]);
        }

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'active' => 'required|boolean',
            'company_uuid' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen létrehozás",
                'errors' => $validator->errors()
            ], 500);
        }
        try {
            Group::create([
                'name' => $request->name,
                'active' => $request->active,
                'company_uuid' => $request->company_uuid
            ]);
            return response()->json(['status' => 'success', 'message' => 'Sikeres mentés!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Sikertelen mentés!'], 500);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'active' => 'required|boolean',
            'company_uuid' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen létrehozás",
                'errors' => $validator->errors()
            ], 500);
        }
        try {
            Group::where('uuid', $id)->update([
                'name' => $request->name,
                'active' => $request->active,
                'company_uuid' => $request->company_uuid
            ]);
            return response()->json(['status' => 'success', 'message' => 'Sikeres mentés!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Sikertelen mentés!'], 500);
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
        try{
            $res = Group::where('uuid', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Sikeres törlés!'], 200);
        }catch (\Exception $e){
            return response()->json(['status' => 'error', 'message' => 'Sikertelen törlés!'], 500);
        }
    }
}
