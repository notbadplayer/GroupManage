<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGroup;
use App\Models\Group;
use App\Models\Subgroup;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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
        $users = User::get();
        return view('subgroup.edit', [
            'group' => $Group,
            'users' => $users
        ]);
    }


    public function store(UpdateGroup $request): RedirectResponse
    {
        $groupId = $request->groupId;
        $group = Group::find($groupId);

        $data = $request->validated();

        $subgroup=Subgroup::create([
            'name' => $data['name'],
            'group_id' => $groupId,
        ]);

        //dodanie użytkowników do podgrupy: tabela subgroup_user
        $subgroup->users()->attach($request->members);

        return redirect()->route('groups.edit', $groupId)
                ->with('success',"$group->name : dodano podgrupę: $subgroup->name");
    }


    public function edit(Subgroup $Subgroup)
    {
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
        $groupId = $request->groupId;
        $group = Group::find($groupId);

        $data = $request->validated();

        $Subgroup->update([
            'name' => $data['name'],
            'group_id' => $groupId,
        ]);

        //synchronizacja użytkowników w grupie: tabela subgroup_user
        $Subgroup->users()->sync($request->members);

        return redirect()->route('groups.edit', $groupId)
                ->with('success',"Zapisano zmiany");
    }


    public function destroy(Subgroup $subgroup)
    {
        //
    }
}
