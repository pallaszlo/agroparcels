<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory, Uuids;
    protected $primaryKey = 'uuid';
    public $fillable = [
        'work_uuid',
        'layer_uuid',
        'season_id',
        'status_id',
        'treatment_uuid',
        'treatment_quantity',
    ];

    public function work()
    {
        return $this->belongsTo(Work::class, 'work_uuid', 'uuid');
    }

    public function layer()
    {
        return $this->belongsTo(Layer::class, 'layer_uuid', 'uuid');
    }

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id', 'id');
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class, 'treatment_uuid', 'uuid');
    }

    public function status()
    {
        return $this
            ->belongsTo(Status::class, 'status_id', 'id');
    }
    public function parcel()
    {
        return $this
            ->hasManyThrough(Parcel::class, Layer::class, 'parcel_uuid', 'layer_uuid', 'uuid', 'uuid');
    }

    public function getGroup()
    {
        return $this->parcel->group;
    }

    public function persons()
    {
        return $this
            ->belongsToMany(Person::class, 'persons_has_operations', 'operation_uuid', 'person_uuid', 'uuid', 'uuid')
                ->withPivot('hours_worked');
    }
}
