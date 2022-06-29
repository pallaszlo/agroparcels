<?php

namespace App\Http\Controllers;

use App\Models\Culture;
use App\Models\Disease;
use App\Models\FitoProduct;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all Treatments with uuid and name and return them as JSON
        $treatments = Treatment::all();
        $data = [];
        foreach($treatments as $treatment){
            array_push($data, [
                'uuid' => $treatment->uuid,
                'disease_uuid' => $treatment->disease_uuid,
                'disease_name' => $treatment->disease->name,
                'fitoproduct_uuid' => $treatment->fitoproduct_uuid,
                'fitoproduct_name' => $treatment->fitoProduct->name,
                'culture_uuid' => $treatment->culture_uuid,
                'culture_name' => $treatment->culture->name,
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
        //get all Diseases, fitoproducts and cultures and return them as JSON in individual arrays
        $diseases = Disease::all();
        $fitoproducts = FitoProduct::all();
        $cultures = Culture::all();
        $data = [
            'diseases' => [],
            'fitoproducts' => [],
            'cultures' => [],
        ];
        foreach($diseases as $disease){
            array_push($data['diseases'], [
                'uuid' => $disease->uuid,
                'name' => $disease->name,
            ]);
        }
        foreach($fitoproducts as $fitoproduct){
            array_push($data['fitoproducts'], [
                'uuid' => $fitoproduct->uuid,
                'name' => $fitoproduct->name,
            ]);
        }
        foreach($cultures as $culture){
            array_push($data['cultures'], [
                'uuid' => $culture->uuid,
                'name' => $culture->name,
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
            'disease_uuid' => 'required|string|max:255',
            'fitoproduct_uuid' => 'required|string|max:255',
            'culture_uuid' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen létrehozás",
                'errors' => $validator->errors()
            ], 500);
        }
        try{
            $treatment = Treatment::create([
                'disease_uuid' => $request->disease_uuid,
                'fitoproduct_uuid' => $request->fitoproduct_uuid,
                'culture_uuid' => $request->culture_uuid,
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
        $treatment = Treatment::with(['disease','culture', 'fitoproduct'])->where('uuid', $id)->first();

        $data = [
            'uuid' => $treatment->uuid,
            'disease_uuid' => $treatment->disease_uuid,
            'disease_name' => $treatment->disease->name,
            'fitoproduct_uuid' => $treatment->fitoproduct_uuid,
            'fitoproduct_name' => $treatment->fitoProduct->name,
            'culture_uuid' => $treatment->culture_uuid,
            'culture_name' => $treatment->culture->name,
            'fitoproduct_sign' => $treatment->fitoproduct->unit->sign,
        ];
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
        //validate request and update treatment
        $validator = Validator::make($request->all(), [
            'disease_uuid' => 'required|string|max:255',
            'fitoproduct_uuid' => 'required|string|max:255',
            'culture_uuid' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen létrehozás",
                'errors' => $validator->errors()
            ], 500);
        }
        try{
            $treatment = Treatment::where('uuid', $id)->update([
                'disease_uuid' => $request['disease_uuid'],
                'fitoproduct_uuid' => $request['fitoproduct_uuid'],
                'culture_uuid' => $request['culture_uuid'],
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        //delete parcel with uuid
        try{
            Treatment::where('uuid', $id)->delete();
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen törlés",
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
