<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Group;
use App\Models\Layer;
use App\Models\Operation;
use App\Models\Parcel;
use App\Models\Person;
use App\Models\Season;
use App\Models\Status;
use App\Models\Treatment;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPUnit\TextUI\XmlConfiguration\Groups;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $operations = [];
        if ($user->hasRole('super-admin')) {
            $operations = Operation::withCount(['persons'])->get();
        } else if ($user->hasRole('admin')) {
            $groups = $user->getPossibleGroups();

            $parcels = Parcel::whereIn('group_uuid', $groups)->get('uuid');
            $layers = Layer::whereIn('parcel_uuid', $parcels)->get('uuid');

            $operations = Operation::whereIn('layer_uuid', $layers)->withCount(['persons'])->get();
        } else {
            $works = $user->person->works->pluck('uuid')->toArray();
            $parcels = Parcel::where('group_uuid', $user->person->group_uuid)->get('uuid');
            $layers = Layer::whereIn('parcel_uuid', $parcels)->get('uuid');

            $operations = Operation::whereIn('layer_uuid', $layers)->whereIn('work_uuid', $works)->withCount(['persons'])->get();
        }

        $data = [
            'operations' => [],
            'statuses' => Status::all(),
        ];


        foreach ($operations as $operation) {
            $hours_worked = 0;
            $worked = 0;
            foreach ($operation->persons as $person) {
                $hours_worked += $person->pivot->hours_worked;
                if (isset($user->person)) {
                    if ($person->uuid == $user->person->uuid) {
                        $worked += $person->pivot->hours_worked;
                    }
                }
            }
            $data['operations'][] = [
                'uuid' => $operation->uuid,
                'work_uuid' => $operation->work->uuid,
                'work_name' => $operation->work->name,
                'layer_uuid' => $operation->layer->uuid,
                'layer_name' => $operation->layer->name,
                'season_id' => $operation->season->id,
                'description' => $operation->description ?? 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum, quas.',
                'season_name' => $operation->season->name,
                'status_id' => $operation->status->id,
                'treatment_uuid' => $operation->treatment->uuid ?? null,
                'treatment_quantity' => $operation->treatment_quantity,
                'persons' => $operation->persons_count,
                'worked_hours' => $hours_worked,
                'person_worked' => $worked,
            ];
        }

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = $request->user();
        $data = [];
        $groups = [];
        $treatments = [];
        $seasons = [];
        $layers = [];
        $works = [];
        $statuses = [];

        if ($user->hasRole('super-admin')) {
            $groups = Group::get(['uuid']);
        } else {
            $person = Person::where('user_id', $user->id)->first();

            $group = $person->group;
            $groups = [];

            if ($group->is_company_leader) {
                $groups = Group::where('company_id', $group->company_id)->get(['uuid']);
            } else {
                $groups[] = $group->uuid;
            }
        }

        $seasons = Season::all(['id', 'name']);
        $treatments = Treatment::get();
        $layers = Layer::with(['parcel'])->whereHas('parcel', function ($q) use ($groups) {
            $q->whereIn('group_uuid', $groups);
        })->get(['uuid', 'name']);
        $works = Work::all();
        $statuses = Status::all();

        foreach ($treatments as $treatment) {
            $data['treatments'][] = [
                'uuid' => $treatment->uuid,
                'name' => $treatment->disease->name . ' - ' . $treatment->fitoproduct->name . ' - ' . $treatment->culture->name,
                'disease_uuid' => $treatment->disease->uuid,
                'fitoproduct_uuid' => $treatment->fitoproduct->uuid,
                'culture_uuid' => $treatment->culture->uuid,
            ];
        }

        $data = [
            ...$data,
            'seasons' => $seasons,
            'layers' => $layers,
            'works' => $works,
            'statuses' => $statuses,
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
        $validator = Validator::make($request->all(), [
            'work_uuid' => 'required|uuid',
            'layer_uuid' => 'required|uuid',
            'season_id' => 'required|integer',
            'description' => 'nullable|string',
            'status_id' => 'required|integer',
            'treatment_uuid' => 'nullable|uuid',
            'treatment_quantity' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen létrehozás",
                'errors' => $validator->errors()
            ], 500);
        }

        try {
            $operation = Operation::create([
                'work_uuid' => $request->work_uuid,
                'layer_uuid' => $request->layer_uuid,
                'season_id' => $request->season_id,
                'description' => $request->description,
                'status_id' => $request->status_id,
                'treatment_uuid' => $request->treatment_uuid ?? null,
                'treatment_quantity' => $request->treatment_quantity ?? null,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => "Sikeres létrehozás",
                'operation' => $operation
            ], 200);
        } catch (\Exception $e) {
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
     * @param  \App\Models\Operation  $operation
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Operation $operation)
    {
        $data = [
            'operation_uuid' => $operation->uuid,
            'work_uuid' => $operation->work->uuid,
            'work_name' => $operation->work->name,
            'layer_uuid' => $operation->layer->uuid,
            'layer_name' => $operation->layer->name,
            'layer_culture' => isset($operation->layer->culture_uuid) ? $operation->layer->culture->name : null,
            'layer_coordinates' => json_encode($operation->layer->data),
            'parcel_uuid' => $operation->layer->parcel->uuid,
            'parcel_name' => $operation->layer->parcel->name,
            'group_uuid' => $operation->layer->parcel->group->uuid,
            'group_name' => $operation->layer->parcel->group->name,
            'season_id' => $operation->season->id,
            'season_name' => $operation->season->name,
            'season_from' => $operation->season->from,
            'season_to' => $operation->season->to,
            'description' => $operation->description ?? 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum, quas.',
            'status_id' => $operation->status->id,
            'status_name' => $operation->status->name,
            'treatment_uuid' => $operation->treatment->uuid ?? null,
            'treatment_quantity' => $operation->treatment_quantity,
            'disease_uuid' => isset($operation->treatment) ? $operation->treatment->disease->uuid : null,
            'fitoproduct_uuid' => isset($operation->treatment) ? $operation->treatment->fitoproduct->uuid : null,
            'culture_uuid' => isset($operation->treatment) ? $operation->treatment->culture->uuid : null,
            'disease_name' => isset($operation->treatment) ? $operation->treatment->disease->name : null,
            'fitoproduct_name' => isset($operation->treatment) ? $operation->treatment->fitoproduct->name : null,
            'fitoproduct_sign' => isset($operation->treatment) ? $operation->treatment->fitoproduct->unit->sign : null,
            'culture_name' => isset($operation->treatment) ? $operation->treatment->culture->name : null,
        ];

        $user = $request->user();
        if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
            $persons = $operation->persons;
            $data['persons'] = [];

            foreach ($persons as $person) {
                $data['persons'][] = [
                    'person_uuid' => $person->uuid,
                    'person_name' => $person->name,
                    'hours_worked' => $person->pivot->hours_worked,
                ];
            }
        } else {
            $data['hours_worked'] = 0;
            if($operation->persons->contains($user->person)) {
                $data['hours_worked'] = $operation->persons->where('id', $user->person->id)->first()->pivot->hours_worked;
            }
        }

        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Operation  $operation
     * @return \Illuminate\Http\Response
     */
    public function edit(Operation $operation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Operation  $operation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Operation $operation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Operation  $operation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Operation $operation)
    {
        //search operation with uuid and delete
        $operation = Operation::where('uuid', $operation->uuid)->first();
        $operation->delete();
    }
    public function changeStatus($opUuid, $stausId)
    {
        $operation = Operation::where('uuid', $opUuid)->first();
        $operation->status_id = $stausId;
        $operation->save();
        return response()->json(['status' => 'ok']);
    }
    public function changeHoursWorked(Request $request, $opUuid, $personUuid)
    {
        $validator = Validator::make($request->all(), [
            'hours' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen módosítás",
                'errors' => $validator->errors()
            ], 500);
        }
        try {
            $operation = Operation::where('uuid', $opUuid)->first();
            $person = Person::where('uuid', $personUuid)->first();

            if ($request->hours == 0) {
                $operation->persons()->detach($person);
                $operation->save();
            } else {
                if ($operation->persons()->where('person_uuid', $person->uuid)->exists()) {
                    $operation->persons()->updateExistingPivot($person, ['hours_worked' => $request->hours], false);
                    $operation->save();
                } else {
                    $operation->persons()->attach($person, ['hours_worked' => $request->hours]);
                    $operation->save();
                }
            }


            return response()->json([
                'status' => 'success',
                'message' => "Sikeres módosítás",
                'operation' => $operation
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen módosítás",
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function changeTreatmentQuantity(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen módosítás",
                'errors' => $validator->errors()
            ], 500);
        }
        try {
            $operation = Operation::where('uuid', $uuid)->first();
            $operation->treatment_quantity = $request->quantity;
            $operation->save();

            return response()->json([
                'status' => 'success',
                'message' => "Sikeres módosítás",
                'operation' => $operation
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "Sikertelen módosítás",
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function getPossiblePersons($opUuid){
        $operation = Operation::where('uuid', $opUuid)->first();
        $group = $operation->layer->parcel->group->uuid;
        $work = $operation->work;

        $possiblePersons = $work->persons->where('group_uuid', $group);

        foreach($possiblePersons as $person){
            if($operation->persons->contains($person)){
                $person['worked_hours'] = $operation->persons()->where('person_uuid', $person->uuid)->first()->pivot->hours_worked;
            }
        }

        return response()->json([$possiblePersons], 200);
    }
}
