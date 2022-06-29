<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    use HasFactory, Uuids;
    protected $primaryKey = 'uuid';
    public $fillable = [
        'group_uuid',
        'name',
        'identifier',
        'data'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_uuid', 'uuid');
    }

    public function layers()
    {
        return $this->hasMany(Layer::class, 'parcel_uuid', 'uuid');
    }

    public function operations()
    {
        return $this->hasManyThrough(Operation::class, Layer::class, 'parcel_uuid', 'layer_uuid', 'uuid', 'uuid');
    }
}
