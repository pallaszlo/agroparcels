<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory, Uuids;

    public $timestamps = false;
    protected $table = 'persons';
    protected $primaryKey = 'uuid';
    public $fillable = [
        'name',
        'group_uuid',
        'user_id',
    ];

    public function works()
    {
        return $this->belongsToMany(Work::class, "person_has_work", 'person_uuid', 'work_uuid', 'uuid', 'uuid');
    }

    public function work_machines()
    {
        return $this->belongsToMany(Work_Machine::class, 'work_has_work_machine', 'work_machine_uuid', 'person_uuid', 'uuid', 'uuid')->withPivot('work_machine_uuid', 'person_uuid', 'work_uuid');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_uuid', 'uuid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function operations()
    {
        return $this->belongsToMany(Operation::class, 'persons_has_operations', 'person_uuid', 'operation_uuid', 'uuid', 'uuid')->withPivot('person_uuid', 'operation_uuid', 'hours_worked');
    }
}
