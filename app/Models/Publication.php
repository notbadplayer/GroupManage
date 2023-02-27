<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'allowComments',
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

    //grupy do publikacji: samo ID
    public function groupsIDs()
    {
        return $this->groups->pluck('id')->toArray();
    }
    //podgrupy do publikacji: samo ID
    public function subgroupsIDs()
    {
        return $this->subgroups->pluck('id')->toArray();
    }
    //użytkownicy do publikacji: samo ID
    public function usersIDs()
    {
        return $this->users->pluck('id')->toArray();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function questionnaire(): HasOne
    {
        return $this->hasOne(Questionnaire::class);
    }

    public function restrictedVisibilityUserIds()
    {
    // Get the group IDs associated with the publication
    $groupIds = $this->groups->pluck('id');

    // Get the subgroup IDs associated with the publication
    $subgroupIds = $this->subgroups->pluck('id');

    // Get the user IDs associated with the groups and subgroups
    $userIds = User::whereIn('id', function ($query) use ($groupIds, $subgroupIds) {
        $query->select('user_id')
            ->from('group_user')
            ->whereIn('group_id', $groupIds)
            ->orWhereIn('subgroup_id', $subgroupIds);
    })->pluck('id')->toArray();

    dd($userIds);


    }
}
