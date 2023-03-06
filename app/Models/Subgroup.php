<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subgroup extends Model
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
        'group_id'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    //członkowie podgrupy
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_user')->withPivot('group_id');
    }

    //członkowie podgrupy: same ID
   public function members()
    {
        return $this->users->pluck('id')->unique()->toArray();
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
}
