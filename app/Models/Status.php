<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'id';
    public $fillable = [
        'name',
        'is_done',
        'to',
    ];

    public function operations(){
        return $this->hasMany(Operation::class, 'status_id', 'id');
    }

}
