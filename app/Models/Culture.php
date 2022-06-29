<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Culture extends Model
{
    use HasFactory, Uuids;

    public $timestamps = false;
    protected $primaryKey = 'uuid';
    public $fillable = [
        'name'
    ];

    public function treatments()
    {
        return $this->hasMany(Treatment::class, 'culture_uuid', 'uuid');
    }

}
