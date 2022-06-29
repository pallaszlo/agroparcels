<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory, Uuids;

    public $timestamps = false;
    protected $primaryKey = 'uuid';
    public $fillable = [
        'company_uuid',
        'name',
        'created_by',
        'active'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_uuid', 'uuid');
    }

    public function parcels()
    {
        return $this->hasMany(Parcel::class, 'group_uuid', 'uuid');
    }

    public function work_machines(){
        return $this->hasMany(WorkMachine::class, 'group_uuid', 'uuid');
    }

    public function persons(){
        return $this->hasMany(Person::class, 'group_uuid', 'uuid');
    }
}
