<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory, Uuids;

    public $timestamps = false;
    protected $primaryKey = 'uuid';
    public $fillable = [
        'fitoproduct_uuid',
        'disease_uuid',
        'culture_uuid',
    ];

    public function operation(){
        return $this->hasMany(Operation::class, 'treatment_uuid', 'uuid');
    }

    public function disease()
    {
        return $this->hasOne(Disease::class, 'uuid', 'disease_uuid');
    }

    public function fitoproduct()
    {
        return $this->hasOne(Fitoproduct::class, 'uuid' , 'fitoproduct_uuid');
    }

    public function culture()
    {
        return $this->hasOne(Culture::class, 'uuid' , 'culture_uuid');
    }


}
