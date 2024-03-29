<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'users_chor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     *

     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
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

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function subgroups(): BelongsToMany
    {
        return $this->belongsToMany(Subgroup::class, 'group_user');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class)->withPivot('subgroup_id');
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

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
