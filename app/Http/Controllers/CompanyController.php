<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        $data = array();

        foreach($companies as $company){

            array_push($data, [
                'uuid' => $company->uuid,
                'name' => $company->name,
                'active' => $company->active,
                'created_by' => $company->created_by,
                'created_by_name' => $company->user->name
            ]);
        }

        return response()->json($data);
    }

    public function update($id, Request $request)
    {
        //dd($request);
        try {
            Company::where('uuid', $id)->update([
                'name'           => $request->name,
                'active'         => $request->active,
            ]);
            return response()->json(['status' => 'success', 'message' => 'Cég sikeresen frissítve!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Sikertelen frissítés!'], 500);
        }
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'active' => 'required|boolean',
            'created_by' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen létrehozás",
                'errors' => $validator->errors()
            ], 500);
        }
        try {
            Company::create([
                'name' => $request->name,
                'active' => $request->active,
                'created_by' => $request->created_by
            ]);
            return response()->json(['status' => 'success', 'message' => 'Cég sikeresen mentve!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Sikertelen mentés!'], 500);
        }
    }

    public function destroy($id)
    {
        try{
            $res = Company::where('uuid', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Cég sikeresen törölve!'], 200);
        }catch (\Exception $e){
            return response()->json(['status' => 'error', 'message' => 'Sikertelen törlés!'], 500);
        }
    }
}
