<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory, Uuids;

    public $timestamps = false;
    protected $primaryKey = 'uuid';
    public $fillable = [
        'name'
    ];

    public function operations()
    {
        return $this->hasMany(Operation::class, 'work_uuid', 'uuid');
    }

    public function work_machines(){
        return $this->belongsToMany(Work_Machine::class, 'work_has_work_machine', 'work_uuid', 'work_machine_uuid', 'uuid', 'uuid');
    }

    public function persons()
    {
        return $this->belongsToMany(Person::class, 'person_has_work', 'work_uuid', 'person_uuid', 'uuid', 'uuid');
    }
}
