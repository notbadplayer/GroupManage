<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGroup;
use App\Models\Group;
use App\Models\Subgroup;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class SubgroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
    }

    public function create(Group $Group): View
    {
        Gate::authorize('admin-level');
        $users = User::get();
        return view('subgroup.edit', [
            'group' => $Group,
            'users' => $users
        ]);
    }


    public function store(UpdateGroup $request): RedirectResponse
    {
        Gate::authorize('admin-level');
        $groupId = $request->groupId;
        $group = Group::find($groupId);

        $data = $request->validated();

        $subgroup=Subgroup::create([
            'name' => $data['name'],
            'group_id' => $groupId,
        ]);

        //dodanie użytkowników do podgrupy: tabela subgroup_user
        //$subgroup->users()->attach($request->members);
        $subgroup->group->users()->attach($request->members, ['subgroup_id' => $subgroup->id ?? null]);

        return redirect()->route('groups.edit', $groupId)
                ->with('success',"$group->name : dodano podgrupę: $subgroup->name");
    }


    public function edit(Subgroup $Subgroup)
    {
        Gate::authorize('admin-level');
        $group = Group::find($Subgroup->group_id);
        $users = User::get();
        $members = $Subgroup->members();

        return view('subgroup.edit', [
            'subgroup' => $Subgroup,
            'group' => $group,
            'users' => $users,
            'members' => $members
        ]);
    }


    public function update(Subgroup $Subgroup, UpdateGroup $request)
    {
        Gate::authorize('admin-level');
        $groupId = $request->groupId;
        $group = Group::find($groupId);

        $data = $request->validated();

        $Subgroup->update([
            'name' => $data['name'],
            'group_id' => $groupId,
        ]);

        $Subgroup->users()->syncWithPivotValues($request->members, ['group_id' => $groupId ?? null]);

        return redirect()->route('groups.edit', $groupId)
                ->with('success',"Zapisano zmiany");
    }


    public function destroy(Subgroup $subgroup)
    {
        //
    }
}
