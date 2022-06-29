<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work_Machine extends Model
{
    use HasFactory, Uuids;

    public $timestamps = false;
    public $fillable = [
    ];

    protected $primaryKey = 'uuid';
    protected $table = 'work_machines';

    public function works()
    {
        return $this->belongsToMany(Work::class, "work_has_work_machine", 'work_machine_uuid', 'work_uuid', 'uuid', 'uuid');
    }

    public function persons()
    {
        return $this->belongsToMany(Person::class, 'work_has_work_machine', 'work_machine_uuid', 'person_uuid', 'uuid', 'uuid')->pivot('work_machine_uuid', 'person_uuid', 'work_uuid');
    }
}
