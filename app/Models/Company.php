<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory, Uuids;

    public $timestamps = false;
    protected $primaryKey = 'uuid';
    public $fillable = [
        'name',
        'active',
        'created_by'
    ];


    public function groups()
    {
        return $this->hasMany(Group::class, 'company_uuid', 'uuid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
