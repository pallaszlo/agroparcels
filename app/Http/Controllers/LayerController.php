<?php

namespace App\Http\Controllers;

use App\Models\Culture;
use App\Models\Layer;
use App\Models\Parcel;
use App\Models\Season;
use App\Models\Treatment;
use App\Models\Work;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $layers = Layer::withCount('operations')->get();
        $data = [];

        foreach($layers as $layer){
            array_push($data, [
                'uuid' => $layer->uuid,
                'name' => $layer->name,
                'parcel_uuid' => $layer->parcel_uuid,
                'culture_uuid' => $layer->culture_uuid,
                'culture_name' => isset($layer->culture) ? $layer->culture->name : '',
                'works_count' => $layer->operations_count,
                'parcel_name' => $layer->parcel->name,
                //'default' => $layer->default,
                'json' => json_encode($layer->data),
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
        //get all Parcels and Seasons and return them as JSON
        $parcels = Parcel::all(['uuid', 'name']);
        $cultures = Culture::all(['uuid', 'name']);

        $data = [
            'parcels' => $parcels,
            'cultures' => $cultures,
        ];
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
        //validate the request and create a new Layer based on it
        //fields are: name, parcel_uuid, default, culture_uuid, data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'parcel_uuid' => 'required|string',
            //'default' => 'required|boolean',
            'coordinates' => 'required',
            'cultrue_uuid' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen létrehozás",
                'errors' => $validator->errors()
            ], 500);
        }

        try {
            $layer = Layer::create([
                'name' => $request->name,
                'parcel_uuid' => $request->parcel_uuid,
                //'default' => $request->default,
                'data' =>  Polygon::fromJson(json_encode($request->coordinates)),
                'culture_uuid' => $request->cultrue_uuid,
            ]);
            return response()->json(['status' => 'success',
            'message' => 'Réteg sikeresen mentve!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error',
            'message' => 'Sikertelen mentés!',
            'message2' => $e->getMessage()], 500);
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

        $layer = Layer::where('uuid', $id)->first();
        $data = [
            'uuid' => $layer->uuid,
            'name' => $layer->name,
            'parcel_uuid' => $layer->parcel_uuid,
            'parcel_name' => $layer->parcel->name,
            'default' => $layer->default,
            'json' => json_encode($layer->data),
            'operations' => [],
            'fitoproducts' => [],
        ];

        $operations = $layer->operations;

        foreach($operations as $i=>$operation){
            $data['operations'][$i] = [
                'uuid' => $operation->uuid,
                'date' => $operation->created_at,
                'season_id' => $operation->season_id,
                'season_name' => $operation->season->name,
                'work_uuid' => $operation->work_uuid,
                'work_name' => $operation->work->name,
                'status_name'   => $operation->status->name,
                'treatment_uuid' => $operation->treatment_uuid,
                'treatment_name' => $operation->treatment_uuid == null ? null : $operation->treatment->fitoproduct->name,
                'quantity' => $operation->treatment_uuid == null ? null : $operation->treatment_quantity . $operation->treatment->fitoproduct->unit->sign,
                'culture_uuid' => $operation->culture_uuid,
                'culture_name' => $operation->layer->culture_uuid == null ? null : $operation->layer->culture->name,
                'worked_hours' => 0,
                'people_count' => 0,
            ];

            foreach($operation->persons as $person){
                $data['operations'][$i]['worked_hours'] += $person->pivot->hours_worked;
                $data['operations'][$i]['people_count'] += 1;
            }

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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            //'default' => 'required|boolean',
            'coordinates' => 'required',
            'culture_uuid' => 'string',
            'parcel_uuid' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen mentés",
                'errors' => $validator->errors(),
                'asd' => $request->all()
            ], 500);
        }
        try {
            Layer::where('uuid', $id)->update([
                'name' => $request->name,
                'parcel_uuid' => $request->parcel_uuid,
                //'default' => $request->default,
                'culture_uuid' => $request->culture_uuid,
                'data' => Polygon::fromJson(json_encode($request->coordinates)),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Sikeres mentés!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Sikertelen mentés!', 'asd' => $e->getMessage()], 500);
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
        //delete Layer where uuid = $id
        try {
            Layer::where('uuid', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Sikeres törlés!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Sikertelen törlés!', 'asd' => $e->getMessage()], 500);
        }
    }
}
