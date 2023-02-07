<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    public function subgroups(): HasMany
    {
        return $this->hasMany(Subgroup::class);
    }

    public function subgroupsIds()
    {
        return $this->subgroups->pluck('id')->toArray();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('subgroup_id');
    }
    // public function members()
    // {
    //     return $this->users->pluck('id')->toArray();
    // }

    public function publications()
    {
        return $this->belongsToMany(Publication::class);
    }


}
