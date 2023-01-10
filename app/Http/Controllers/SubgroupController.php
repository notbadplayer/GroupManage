<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGroup;
use App\Models\Group;
use App\Models\Subgroup;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
        return view('subgroup.edit', ['group' => $Group]);
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

        return redirect()->route('groups.edit', $groupId)
                ->with('success',"$group->name : dodano podgrupę: $subgroup->name");
    }


    public function show(Subgroup $subgroup)
    {
        //
    }


    public function edit(Subgroup $subgroup)
    {
        //
    }


    public function update(Request $request, Subgroup $subgroup)
    {
        //
    }


    public function destroy(Subgroup $subgroup)
    {
        //
    }
}
