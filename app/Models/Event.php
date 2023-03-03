<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'time',
        'restrictedVisibility'
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function subgroups()
    {
        return $this->belongsToMany(Subgroup::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    //grupy do nut: samo ID
    public function groupsIDs()
    {
        return $this->groups->pluck('id')->toArray();
    }
     //podgrupy do nut: samo ID
     public function subgroupsIDs()
     {
         return $this->subgroups->pluck('id')->toArray();
     }
    //użytkownicy do nut: samo ID
    public function usersIDs()
    {
        return $this->users->pluck('id')->toArray();
    }
}
