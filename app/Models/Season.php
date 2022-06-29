<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Season extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'id';
    public $fillable = [
        'name',
        'fron',
        'to',
    ];

    public function operations(){
        return $this->hasMany(Operation::class, 'season_uuid', 'uuid');
    }
}
