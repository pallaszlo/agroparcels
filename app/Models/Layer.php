<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class Layer extends Model
{
    use HasFactory, Uuids, SpatialTrait;

    public $timestamps = false;
    public $primaryKey = 'uuid';
    public $fillable = [
        'parcel_uuid',
        'name',
        'data',
        'default',
        'culture_uuid',
    ];

    protected $spatialFields = [
        'data'
    ];

    public function parcel()
    {
        return $this->belongsTo(Parcel::class, 'parcel_uuid', 'uuid');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class, 'layer_uuid', 'uuid');
    }

    public function culture()
    {
        return $this->belongsTo(Culture::class, 'culture_uuid', 'uuid');
    }

}
