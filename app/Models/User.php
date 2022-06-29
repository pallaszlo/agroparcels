<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function companies()
    {
        return $this->hasMany(Company::class, 'created_by', 'id');
    }
    public function person()
    {
        return $this->hasOne(Person::class, 'user_id', 'id');
    }

    public function getPossibleGroups()
    {
        $groups = [];

        if ($this->hasRole('super-admin')) {
            $groups = Group::all(['uuid']);
        } else {
           if($this->person->group->is_company_leader){
               $company = $this->person->group->company;

                $groups = Group::where('company_uuid', $company->uuid)->get(['uuid']);
           }
           else{
               $groups = Group::where('uuid', $this->person->group->uuid)->get(['uuid']);
           }
        }
        return $groups;
    }
}
