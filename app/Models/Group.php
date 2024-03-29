<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];


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
    public function members()
    {
        return $this->users->pluck('id')->toArray();
    }

    public function publications()
    {
        return $this->belongsToMany(Publication::class);
    }

    public function notes()
    {
        return $this->belongsToMany(Note::class);
    }

    public function songs()
    {
        return $this->belongsToMany(Song::class);
    }


    public function userIds()
    {
        return $this->users()->pluck('id');
    }

}
