<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiseaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all Diseases with uuid and name and return them as JSON
        $diseases = Disease::all();
        $data = [];
        foreach($diseases as $disease){
            array_push($data, [
                'uuid' => $disease->uuid,
                'name' => $disease->name
            ]);
        }
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen létrehozás",
                'errors' => $validator->errors()
            ], 500);
        }
        try{
            $disease = Disease::create([
                'name' => $request->name,
            ]);
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
        //validate request and update disease
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen létrehozás",
                'errors' => $validator->errors()
            ], 500);
        }
        try{
            $disease = Disease::find($id);
            $disease->name = $request->name;
            $disease->save();
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
            Disease::where('uuid', $id)->delete();
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen törlés",
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
