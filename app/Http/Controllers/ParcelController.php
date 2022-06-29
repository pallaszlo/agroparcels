<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Parcel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParcelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all parcels with their group name and return them as JSON
        //fields are: uuid, group_uuid, group_name, name, identifier, data, created_at, updated_at
        $parcels = Parcel::withCount('layers')->get();
        $data = [];

        foreach($parcels as $parcel){
            array_push($data, [
                'uuid' => $parcel->uuid,
                'group_uuid' => $parcel->group_uuid,
                'group_name' => $parcel->group->name,
                'layers_count' => $parcel->layers_count,
                'name' => $parcel->name,
                'identifier' => $parcel->identifier,
                'data' => json_encode($parcel->data),
                'created_at' => $parcel->created_at,
                'updated_at' => $parcel->updated_at
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
        $groups = Group::all();

        $data = [];
        foreach($groups as $group){
            array_push($data, [
                'uuid' => $group->uuid,
                'name' => $group->name
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
            'name' => 'required|string|max:255',
            'identifier' => 'required|string|max:255',
            'data' => 'required|json',
            'group_uuid' => 'required|uuid',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen létrehozás",
                'errors' => $validator->errors()
            ], 500);
        }
        try{
            $parcel = Parcel::create([
                'name' => $request->name,
                'identifier' => $request->identifier,
                'data' => $request->data,
                'group_uuid' => $request->group_uuid
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
        $parcel = Parcel::where('uuid', $id)->with('layers')->first();
        $data = [
            'uuid' => $parcel->uuid,
            'name' => $parcel->name,
            'layers' => [],
            'fitoproducts' => [],
        ];

        foreach($parcel->layers as $layer){
            array_push($data['layers'], [
                'uuid' => $layer->uuid,
                'name' => $layer->name,
                'identifier' => $layer->identifier,
                'data' => json_encode($layer->data),
                'culture_name' => isset($layer->culture) ? $layer->culture->name : null,
                'operations_count' => 0,
            ]);

            foreach($layer->operations as $operation){
                $data['layers'][count($data['layers']) - 1]['operations_count']++;

                if($operation->treatment_uuid != null){
                    $name = $operation->treatment->fitoproduct->name;

                    if(isset($data['fitoproducts'][$name])){
                        $data['fitoproducts'][$name]['quantity'] += $operation->treatment_quantity;
                    } else {
                        $data['fitoproducts'][$name] = [
                            'uuid' => $operation->treatment_uuid,
                            'name' => $operation->treatment->fitoproduct->name,
                            'quantity' => $operation->treatment_quantity,
                            'unit' => $operation->treatment->fitoproduct->unit->sign,
                        ];
                    }
                }
            }


        }

        return response()->json($data);
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
        //validate request and update parcel
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'identifier' => 'required|string|max:255',
            'data' => 'required|json',
            'group_uuid' => 'required|uuid',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen módosítás",
                'errors' => $validator->errors()
            ], 500);
        }

        try{
            Parcel::where('uuid', $id)->update([
                'name' => $request->name,
                'identifier' => $request->identifier,
                'data' => $request->data,
                'group_uuid' => $request->group_uuid
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen módosítás",
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
            Parcel::where('uuid', $id)->delete();
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen törlés",
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
